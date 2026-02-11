<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'created_at',
        'created_by_id',
        'created_by_ip',
        'updated_at',
        'updated_by_id',
        'updated_by_ip',
        'name',
        'email',
        'type',
        'photo',
        'password',
        'verified',
        'remember_token',
        'verification_token',
        'reset_token',
        'api_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function fetchAllUsers()
    {
        $users = DB::table('users')
            ->where('type', 1)
            ->orderBy('created_at','desc')
            ->get();

        $usersArr = [];
        if ($users) {
            foreach ($users as $user) {
                $usersArr[] = [
                    'id' => $user->id,
                    'created' => $user->created_at,
                    'name' => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo,
                    'verified' => $user->verified,
                ];
            }
        }

        return $usersArr;
    }

    /**
     * Prepare registration stats for admin dashboard charts
     * @return mixed
     */
    public function registrationAnalytics()
    {
        $months = [];
        for ($m = 0; $m <= 11; $m++) {
            $month = Carbon::today()->startOfMonth()->subMonth($m);
            for ($i = 1; $i <= 3; $i++) {
                $months[$month->month][0] = $month->shortMonthName;
                $months[$month->month][$i] = 0;
            }
        }

        $stats = DB::table('users')
            ->select(
                DB::raw("month(created_at) as monthNumber"),
                DB::raw("monthname(created_at) as monthName"),
                DB::raw("type as type"),
                DB::raw('count(*) as count')
            )
            ->groupBy('monthNumber', 'monthName')
            ->where('type', 1)
            ->where('created_at', '>=', now()->subMonths(12))
            ->get()
            ->groupBy(function ($item)
            {
                return $item->monthNumber;
            })->toArray();

        $data = [];
        foreach ( $months as $monthNumber => $dataBlock ) {
            $data[$dataBlock[0]] = $stats[$monthNumber][0]->count ?? 0;
        }

        return $data;
    }

}
