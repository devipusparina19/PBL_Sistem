namespace App\Http\Controllers;

use App\Models\Milestone;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    public function index()
    {
        $milestones = Milestone::latest()->get();
        return view('milestones.index', compact('milestones'));
    }

    public function create()
    {
        return view('milestones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'judul' => 'required|string|max:255',
            'kelompok' => 'required|string|max:255',
            'rincian' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('dokumentasi', 'public');
        }

        Milestone::create($data);

        return redirect()->route('milestones.index')
                         ->with('success', 'Logbook berhasil ditambahkan!');
    }
}
