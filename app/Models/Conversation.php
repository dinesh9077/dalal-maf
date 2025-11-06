<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'type',               // 1 = customer/vendor/agent; 2 = admin
    'support_ticket_id',
    'reply',
    'file',
  ];

  // --- Fixed relations ---
  public function supportTicket()
  {
    return $this->belongsTo(SupportTicket::class, 'support_ticket_id', 'id');
  }

  // keep concrete relations separate (always valid Relation objects)
  public function admin()
  {
    return $this->belongsTo(Admin::class, 'user_id', 'id');
  }

  public function customer()  // general 'user' (your frontend user)
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  public function vendor()
  {
    return $this->belongsTo(Vendor::class, 'user_id', 'id');
  }

  public function agent()
  {
    return $this->belongsTo(Agent::class, 'user_id', 'id');
  }

  /**
   * Dynamically resolve the sender relation NAME.
   * (Does not return data; returns which relation to use.)
   */
  public function getSenderRelationName(): string
  {
    if ((int) $this->type === 2) {
      return 'admin';
    }

    // use the loaded ticket (lazy-loads if missing)
    $ticketType = optional($this->supportTicket)->user_type;

    return match ($ticketType) {
      'vendor' => 'vendor',
      'agent' => 'agent',
      default => 'customer',
    };
  }

  /**
   * Convenience accessor: returns the loaded sender model (Admin/User/Vendor/Agent).
   * Not an Eloquent relation; just a computed attribute.
   */
  public function getSenderAttribute()
  {
    $relation = $this->getSenderRelationName();
    // Ensure related model is loaded
    $this->loadMissing('supportTicket', $relation);
    return $this->getRelation($relation);
  }
}
