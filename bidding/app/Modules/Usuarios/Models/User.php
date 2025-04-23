<?php

namespace App\Modules\Usuarios\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'telefone',
        'cargo',
        'departamento',
        'is_active',
        'two_factor_enabled',
        'two_factor_secret',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'two_factor_enabled' => 'boolean',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Get the access logs for the user.
     */
    public function accessLogs()
    {
        return $this->hasMany(UserAccessLog::class);
    }

    /**
     * Verificar se o usuário está ativo
     */
    public function isActive()
    {
        return $this->is_active;
    }

    /**
     * Retorna o nome completo do usuário
     */
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    /**
     * Retorna os processos que este usuário é responsável
     */
    public function processos()
    {
        return $this->hasMany(\App\Modules\Processos\Models\Processo::class, 'responsavel_id');
    }

    /**
     * Retorna as notificações do usuário
     */
    public function notificacoes()
    {
        return $this->hasMany(\App\Modules\Notificacoes\Models\Notificacao::class);
    }
}
