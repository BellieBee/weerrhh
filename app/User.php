<?php

namespace Vanguard;

use Vanguard\Presenters\UserPresenter;
use Vanguard\Services\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatable;
use Vanguard\Services\Auth\TwoFactor\Contracts\Authenticatable as TwoFactorAuthenticatableContract;
use Vanguard\Services\Logging\UserActivity\Activity;
use Vanguard\Support\Authorization\AuthorizationUserTrait;
use Vanguard\Support\Enum\UserStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Laracasts\Presenter\PresentableTrait;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Jenssegers\Date\Date;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract,
                                    TwoFactorAuthenticatableContract
{
    use TwoFactorAuthenticatable, CanResetPassword, PresentableTrait, AuthorizationUserTrait;

    protected $presenter = UserPresenter::class;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $dates = ['last_login', 'birthday'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'firma', 
        'username', 
        'first_name', 
        'last_name', 
        'oficina_id', 
        'n_contrato', 
        'cargo_id', 
        'tipo_documento_id', 
        'documento', 
        'fecha_inicio', 
        'fecha_finalizacion',
        'categoria_id',
        'n_afiliacion',
        'n_identificacion_tributaria', 
        'regimen_tributario', 
        'edad', 
        'contacto_emergencia', 
        'tlf_contacto_emergencia', 
        'tipo_sangre', 
        'profesion_id',
        'banco',
        'tipo_cuenta_id', 
        'cuenta',
        'sexo', 
        'cellphone', 
        'skype', 
        'phone', 
        'avatar',
        'address',
        'salario_base', 
        'acumulado_vacaciones', 
        'country_id', 
        'birthday', 
        'last_login', 
        'confirmation_token', 
        'status',
        'group_id', 
        'remember_token'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Always encrypt password when it is updated.
     *
     * @param $value
     * @return string
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function setBirthdayAttribute($value)
    {
        $this->attributes['birthday'] = trim($value) ?: null;
    }

    public function gravatar()
    {
        $hash = hash('md5', strtolower(trim($this->attributes['email'])));

        return sprintf("//www.gravatar.com/avatar/%s", $hash);
    }

    public function isUnconfirmed()
    {
        return $this->status == UserStatus::UNCONFIRMED;
    }

    public function isActive()
    {
        return $this->status == UserStatus::ACTIVE;
    }

    public function isBanned()
    {
        return $this->status == UserStatus::BANNED;
    }

    public function socialNetworks()
    {
        return $this->hasOne(UserSocialNetworks::class, 'user_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'user_id');
    }

    public function oficina()
    {
        return $this->belongsTo('Vanguard\Oficina');
    }

    public function categoria()
    {
        return $this->belongsTo('Vanguard\Categoria');
    }

    public function profesion()
    {
        return $this->belongsTo('Vanguard\Profesion');
    }

    public function cargo()
    {
        return $this->belongsTo('Vanguard\Cargo');
    }

    public function tipo_documento()
    {
        return $this->belongsTo('Vanguard\Tipo_documento');
    }

    public function roles()
    {
        return $this->belongsToMany('Vanguard\Role');
    }   

    public function planilla()
    {
        return $this->hasMany('Vanguard\Empleado_planilla_normal');
    }
    
     public function acumulado()
    {
        return $this->hasMany('Vanguard\Acumulado', 'user_id', 'id');
    }

    public function deduccion()
    {
        return $this->belongsTo('Vanguard\Deduccion');
    }

    public function aporte()
    {
        return $this->belongsTo('Vanguard\Aporte');
    }

    public function contratos()
    {
        return $this->hasMany('Vanguard\Contrato');
    }

    public function reunion()  
    {
        return $this->hasMany('Vanguard\Reunion');
    }
    public function vacaciones()
    {
        return $this->hasMany('Vanguard\Vacaciones');
    }

    public function viajes()
    {
        return $this->hasMany('Vanguard\Viajes');
    }

    public function bono_salud()
    {
        return $this->hasMany('Vanguard\BonoSalud');
    }

    public function tipocuenta()
    {
        return $this->belongsTo('Vanguard\TipoCuentaBancaria', 'tipo_cuenta_id');
    }
}
