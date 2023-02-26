<?php

namespace App\Models;

use App\Enums\ProductSort;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'price',
    ];

    /**
     * Scope a query to get products
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $data
     * @return mixed
     */
    public function scopeGetList($query, $data)
    {
        if ($data['sort']) {
            switch ($data['sort']) {
                case ProductSort::NameAsc->value:
                    $query = $query->orderBy('name', 'asc');
                    break;

                case ProductSort::NameDesc->value:
                default:
                    $query = $query->orderBy('name', 'desc');
                    break;
            }
        }

        return $query;
    }
}
