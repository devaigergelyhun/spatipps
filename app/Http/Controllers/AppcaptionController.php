<?php

namespace App\Http\Controllers;

use App\Models\Appcaption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AppcaptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AppCaption::query();        
        
        if ($request->filled('expression')) {
            $query->where('placeholder', 'like', '%' . $request->expression . '%');
        }

        if ($request->filled('hu')) {
            $query->where('hu', 'like', '%' . $request->hu . '%');
        }

        if ($request->filled('en')) {
            $query->where('en', 'like', '%' . $request->en . '%');
        }

        if ($request->filled('de')) {
            $query->where('de', 'like', '%' . $request->de . '%');
        }

        $appcaptions = $query->orderBy('langfile')
            ->paginate(15)
            ->withQueryString(); // keresés megmarad lapozásnál

        return view('appcaptions.index', compact('appcaptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('appcaptions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'langfile' => 'required|string|max:20',
            'placeholder' => 'nullable|string',
            'hu' => 'nullable|string',
            'en' => 'nullable|string',
            'de' => 'nullable|string',
        ]);

        Appcaption::create($request->all());

        return redirect()->route('appcaptions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appcaption $appcaption)
    {
        return view('appcaptions.edit', compact('appcaption'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appcaption $appcaption)
    {
        $request->validate([
            'langfile' => 'required|string|max:20',
            'placeholder' => 'nullable|string',
            'hu' => 'nullable|string',
            'en' => 'nullable|string',
            'de' => 'nullable|string',
        ]);

        $appcaption->update($request->all());

        return redirect()->route('appcaptions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appcaption $appcaption)
    {
        $appcaption->delete();
        return redirect()->route('appcaptions.index');
    }
    
    private function flattenTranslations(array $array, string $prefix = ''): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $newKey = $prefix === '' ? $key : $prefix . '.' . $key;

            if (is_array($value)) {
                $result += $this->flattenTranslations($value, $newKey);
            } else {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }
    
    private function readTranslationsFromFiles() {
        // 1️⃣ foundinfile = 0 minden rekordnál
        Appcaption::query()->update(['foundinfile' => 0]);

        $langPath = lang_path();

        foreach (File::directories($langPath) as $langDir) {
            $lang = basename($langDir); // pl. hu, en, de

            foreach (File::files($langDir) as $file) {
                
                if ($file->getExtension() !== 'php') {
                    continue;
                }
                
                $filename = pathinfo($file, PATHINFO_FILENAME); // pl. messages

                $translations = include $file->getPathname();

                $this->loopTranlastionFiles($translations, $filename, $lang);
            }
        }       
    }
    
    private function loopTranlastionFiles($translations, $filename, $lang) {
        foreach ($translations as $key => $value) {

            Appcaption::updateOrCreate(
                [
                    'langfile' => $filename,
                    'placeholder' => $key,
                ],
                [
                    $lang => $value,
                    'foundinfile' => 1,
                    'foundincode' => 0,
                ]
            );
        }
    }
    
    private function searchTranslationsInCode()
    {
        // 1️⃣ foundincode reset
        Appcaption::query()->update(['foundincode' => 0]);

        // 2️⃣ vizsgált mappák
        $paths = [
            app_path(),
            resource_path('views'),
            base_path('routes'),
            resource_path('js'),
        ];

        foreach ($paths as $path) {

            if (!File::exists($path)) {
                continue;
            }

            $files = File::allFiles($path);

            foreach ($files as $file) {

                $ext = $file->getExtension();

                if (!in_array($ext, ['php', 'js', 'vue', 'blade.php'])) {
                    continue;
                }

                $content = File::get($file->getRealPath());

                preg_match_all(
                    "/__\(['\"]([^'\"]+)['\"]\)|@lang\(['\"]([^'\"]+)['\"]\)|trans\(['\"]([^'\"]+)['\"]\)/",
                    $content,
                    $matches
                );

                $keys = array_filter(array_merge($matches[1], $matches[2], $matches[3]));
                $this->parseStrings($keys);
                
            }
        }        
    }
    
    protected function parseStrings($keys) {
        foreach ($keys as $fullKey) {

            if (str_contains($fullKey, '.') && !str_contains($fullKey, ' ')) {
                $this->persistByPlaceholder($fullKey);
            } else {
                $this->persistNewCaption($fullKey);
            }
        }        
    }
    
    protected function persistNewCaption(string $fullKey) {
        $caption = Appcaption::where('en', $fullKey)
            ->first();        
        if ($caption) {
            $caption->update(['foundincode' => 1]);            
        } else {
            Appcaption::create([
                'langfile' => '',
                'placeholder' => '',
                'foundincode' => 1,
                'foundinfile' => 0,
                'en' => $fullKey, 
                'hu' => $fullKey, 
                'de' => $fullKey, 
            ]);            
        }
        
    }
    
    protected function persistByPlaceholder(string $fullKey) {
        [$langfile, $placeholder] = explode('.', $fullKey, 2);
                
        $caption = Appcaption::where('langfile', $langfile)
            ->where('placeholder', $placeholder)
            ->first();

        if ($caption) {
            $caption->update(['foundincode' => 1]);
        } else {
            Appcaption::create([
                'langfile' => $langfile,
                'placeholder' => $placeholder,
                'foundincode' => 1,
                'foundinfile' => 0,
                'en' => '', 
                'hu' => '', 
                'de' => '', 
            ]);
        }         
    }
    
    public function searchtranslations()
    {
        set_time_limit(300); // 5 perc
        $this->readTranslationsFromFiles();
        $this->searchTranslationsInCode();
        return redirect()->route('appcaptions.index')
            ->with('success', 'Fordítások sikeresen importálva.');
    }    
    
    public function exportTranslationsToLangFiles()
    {
        ini_set('max_execution_time', 300);
        set_time_limit(300);

        $languages = ['hu', 'en', 'de'];

        // 1️⃣ adatok lekérése
        $captions = Appcaption::all();
        
        // 2️⃣ adatok csoportosítása: lang -> file -> key => value
        $data = [];

        foreach ($captions as $row) {
            foreach ($languages as $lang) {
                if ($row->$lang !== null && $row->$lang !== '') {
                    $data[$lang][$row->langfile][$row->placeholder] = $row->$lang;
                }
            }
        }
        
        // 3️⃣ lang fájlok generálása
        
        $s = [];
        
        foreach ($languages as $lang) {

            $langPath = lang_path($lang);
            
            if (!File::exists($langPath)) {
                File::makeDirectory($langPath, 0755, true);
            }

            foreach ($data[$lang] ?? [] as $fileName => $translations) {

                $filePath = str_replace('/', DIRECTORY_SEPARATOR, "$langPath/$fileName.php");
                
                $s[] = $filePath;

                // 4️⃣ backup (régi fájl átnevezése)
                if (File::exists($filePath)) {
                    $backupName = $filePath . '.bak_' . date('Ymd_His');
                    File::move($filePath, $backupName);
                }
                
                // 5️⃣ PHP lang file generálása
                $phpContent = "<?php\n\nreturn " . var_export($translations, true) . ";\n";
                
                File::put($filePath, $phpContent);
            }
        }
        
        return redirect()->route('appcaptions.index')
            ->with('success', __('messages.lang_files_created'));
    }
    
    
}