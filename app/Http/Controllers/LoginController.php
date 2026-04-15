<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AgeTarj;
use App\Models\Agente;
use App\Models\ContraseniaWeb;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'tarjeta' => ['required'], 
            'password' => ['required'],
        ]);

        $inputIdentificador = $credentials['tarjeta'];
        $passwordProporcionada = $credentials['password'];
        $agente = null;

        // A. BUSCAR AL AGENTE
        // 1. Por DNI
        $agente = Agente::where('age_numdoc', $inputIdentificador)->where('age_activo', 1)->first();

        // 2. Por ID de Agente (Legajo)
        if (!$agente && is_numeric($inputIdentificador)) {
            $agente = Agente::where('age_id', $inputIdentificador)->where('age_activo', 1)->first();
        }

        // 3. Por Número de Tarjeta
        if (!$agente) {
            $tarjetaAsociada = AgeTarj::where('tarj_nro', $inputIdentificador)
                ->where('agetarj_activa', 1)
                ->first();
            if ($tarjetaAsociada) {
                $agente = Agente::where('age_id', $tarjetaAsociada->age_id)->where('age_activo', 1)->first();
            }
        }

        if (!$agente) {
            return back()->withErrors(['tarjeta' => 'Usuario no encontrado o inactivo.']);
        }

        // B. VALIDAR CONTRASEÑA
        $authenticated = false;

        // 1. Opción A: La contraseña es su DNI (Regla: "hasta que lo cambien")
        if ($passwordProporcionada === $agente->age_numdoc) {
            $authenticated = true;
            // Si no tiene hash todavía, lo inicializamos
            if (empty($agente->age_password_hash)) {
                $agente->update(['age_password_hash' => \Illuminate\Support\Facades\Hash::make($passwordProporcionada)]);
            }
        }

        // 2. Opción B: Validar contra el hash en t_agente (Bcrypt)
        if (!$authenticated && !empty($agente->age_password_hash)) {
            if (\Illuminate\Support\Facades\Hash::check($passwordProporcionada, $agente->age_password_hash)) {
                $authenticated = true;
            }
        }

        // 3. Opción C: Legacy - Validar contra t_contrasenias_web (Texto plano o MD5)
        if (!$authenticated) {
            $tarjeta = AgeTarj::where('age_id', $agente->age_id)->where('agetarj_activa', 1)->first();
            if ($tarjeta) {
                $cw = ContraseniaWeb::where('cw_tar_id', $tarjeta->tarj_nro)->first();
                if ($cw) {
                    // Verificamos exacto (Legacy suele ser plain text)
                    if ($cw->cw_pass === $passwordProporcionada) {
                        $authenticated = true;
                        // Migramos al nuevo esquema de hash
                        $agente->update(['age_password_hash' => \Illuminate\Support\Facades\Hash::make($passwordProporcionada)]);
                    }
                }
            }
        }

        if (!$authenticated) {
            return back()->withErrors(['tarjeta' => 'Contraseña incorrecta.']);
        }

        // C. LOGUEAR
        Auth::login($agente);
        $request->session()->regenerate();

        return redirect()->intended('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}