<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Film
 *
 * @property int $id
 * @property string $title
 * @property int $year
 * @property int $rate
 * @property string $country_id
 * @property string|null $cover
 * @property string|null $source
 * @property string|null $torrent_file
 * @property int $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Film newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Film newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Film query()
 * @method static \Illuminate\Database\Eloquent\Builder|Film whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Film whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Film whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Film whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Film whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Film whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Film whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Film whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Film whereTorrentFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Film whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Film whereYear($value)
 * @mixin \Eloquent
 * @property string $description
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Genre[] $genres
 * @property-read int|null $genres_count
 * @method static \Illuminate\Database\Eloquent\Builder|Film whereDescription($value)
 */
class Film extends Model
{
    use HasFactory;

    public function genres(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }
}
