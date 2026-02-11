<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Withdrawals extends Model
{
    use HasFactory;
    protected $table = "withdrawals";
    protected $fillable = [
        'created_at',
        'created_by_id',
        'created_by_ip',
        'updated_at',
        'updated_by_id',
        'updated_by_ip',
        'details',
        'amount',
        'payment_method',
        'status',
    ];

    public function fetchUserWithdrawals(int $userId)
    {
        $withdrawals = DB::table('withdrawals')
            ->join(
                'withdrawal_methods', 
                'withdrawals.payment_method', 
                'withdrawal_methods.id'
            )
            ->select('withdrawals.*')
            ->addSelect('withdrawal_methods.name AS methodName')
            ->where('withdrawals.created_by_id', $userId)
            ->orderBy('withdrawals.created_at','desc')
            ->get();

        $withdrawalsArr = [];
        if ($withdrawals->isNotEmpty()) {
            foreach ($withdrawals as $withdrawal) {
                $withdrawalsArr[] = [
                    'id' => $withdrawal->id,
                    'date' => $withdrawal->created_at,
                    'details' => $withdrawal->details,
                    'amount' => $withdrawal->amount,
                    'status' => $withdrawal->status,
                    'methodName' => $withdrawal->methodName,
                ];
            }
        }

        return $withdrawalsArr;
    }

    public function fetchAllWithdrawals(mixed $status = false)
    {
        $withdrawals = DB::table('withdrawals')
            ->leftJoin(
                'users', 
                'withdrawals.created_by_id', 
                'users.id'
            )
            ->join(
                'withdrawal_methods', 
                'withdrawals.payment_method', 
                'withdrawal_methods.id'
            )
            ->select('withdrawals.*')
            ->addSelect('users.id AS userId','users.name','users.email')
            ->addSelect('withdrawal_methods.name AS methodName')
            ->when($status, function ($query) use ($status): void {
                $query->where('withdrawals.status', '=', $status);
            })
            ->orderBy('withdrawals.created_at','desc')
            ->get();

        $withdrawalsArr = [];
        if ($withdrawals) {
            foreach ($withdrawals as $withdrawal) {
                $userName = $withdrawal->name
                    ? $withdrawal->name
                    : __('lang.deleted_user');
                $userEmail = $withdrawal->email
                    ? $withdrawal->email
                    : __('lang.deleted_user');
                $withdrawalsArr[] = [
                    'id' => $withdrawal->id,
                    'date' => $withdrawal->created_at,
                    'details' => $withdrawal->details,
                    'amount' => $withdrawal->amount,
                    'status' => $withdrawal->status,
                    'userId' => $withdrawal->userId ?? '-',
                    'name' => $userName,
                    'email' => $userEmail,
                    'methodName' => $withdrawal->methodName,
                ];
            }
        }

        return $withdrawalsArr;
    }

    public function fetchWithdrawal(
        int $withdrawalId,
    ) {
        $withdrawal = DB::table('withdrawals')
            ->leftJoin(
                'users', 
                'withdrawals.created_by_id', 
                'users.id'
            )
            ->join(
                'withdrawal_methods', 
                'withdrawals.payment_method', 
                'withdrawal_methods.id'
            )
            ->select('withdrawals.*')
            ->addSelect('users.id','users.name','users.email')
            ->addSelect('withdrawal_methods.name AS methodName')
            ->where('withdrawals.id', '=', $withdrawalId)
            ->first();

        if ($withdrawal) {
            $withdrawalData = [
                'details' => $withdrawal->details,
                'amount' => $withdrawal->amount,
                'gateway' => $withdrawal->methodName,
                'created_by_ip' => $withdrawal->created_by_ip,
            ];
        }

        return $withdrawalData ?? false;
    }
}
