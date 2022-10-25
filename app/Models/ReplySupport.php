<?php

namespace App\Models;

use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReplySupport extends Model
{
    use HasFactory;
    use UuidTrait;

    public $incrementing = false;

    protected $table = 'reply_support';

    protected $keyType = 'uuid';

    protected $fillable = [
        'description',
        'support_id',
        'user_id'
    ];

    public function support()
    {
        return $this->belongsTo(Support::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
