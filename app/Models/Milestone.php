namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $fillable = [
        'tanggal',
        'judul',
        'kelompok',
        'rincian',
        'foto',
    ];
}
