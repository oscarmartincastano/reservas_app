<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Ftp\FtpAdapter;
use League\Flysystem\Ftp\FtpConnectionOptions;


class DatabaseBackUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filename = "backup-" . Carbon::now()->format("Y-m-d_H-i-s") . ".gz";

        $command = "mysqldump --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "  | gzip > " . storage_path() . "/app/backup/" . $filename;

        $returnVar = NULL;
        $output  = NULL;

        exec($command, $output, $returnVar);
        $disk = Storage::build([
            'driver' => 'ftp',
            'host' => "85.55.6.93",
            'username' => "taller",
            'password' => "2016Taller#",
            'port' => 21,
            'ssl' => false,
            'passive' => true,
            'root' => '/copiasautomaticasbd/reservas/gestioninstalacion', ]);

        $disk->put($filename, fopen(storage_path() . "/app/backup/" . $filename, 'r+'));

        $nombre_archivo= "/app/backup/" . $filename;


        unlink(storage_path($nombre_archivo));



    }
}
