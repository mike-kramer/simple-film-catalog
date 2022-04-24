<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Season
 *
 * @property int $id
 * @property int $film_id
 * @property int $number
 * @property string $type
 * @property int $total_episodes
 * @property int $total_episodes_now
 * @property string|null $start_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Film $film
 * @method static \Illuminate\Database\Eloquent\Builder|Season newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Season newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Season query()
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereFilmId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereTotalEpisodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereTotalEpisodesNow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Season extends Model
{
    use HasFactory;

    public function film()
    {
        return $this->belongsTo(Film::class);
    }
}
