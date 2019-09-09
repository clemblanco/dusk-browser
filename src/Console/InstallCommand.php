<?php

namespace DuskScraper\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:install
                {--proxy= : The proxy to download the binary through (example: "tcp://127.0.0.1:9000")}
                {--ssl-no-verify : Bypass SSL certificate verification when installing through a proxy}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Dusk Scraper into the application';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (! is_dir(storage_path('app/dusk-scraper/screenshots'))) {
            $this->createScreenshotsDirectory();
        }

        if (! is_dir(storage_path('app/dusk-scraper/console'))) {
            $this->createConsoleDirectory();
        }

        $this->comment('Downloading ChromeDriver binaries...');

        $driverCommandArgs = ['--all' => true];

        if ($this->option('proxy')) {
            $driverCommandArgs['--proxy'] = $this->option('proxy');
        }

        if ($this->option('ssl-no-verify')) {
            $driverCommandArgs['--ssl-no-verify'] = true;
        }

        $this->call('dusk:chrome-driver', $driverCommandArgs);
    }

    /**
     * Create the screenshots directory.
     *
     * @return void
     */
    protected function createScreenshotsDirectory()
    {
        mkdir(storage_path('app/dusk-scraper/screenshots'), 0755, true);

        file_put_contents(storage_path('app/dusk-scraper/screenshots/.gitignore'), '*
!.gitignore
');
    }

    /**
     * Create the console directory.
     *
     * @return void
     */
    protected function createConsoleDirectory()
    {
        mkdir(storage_path('app/dusk-scraper/console'), 0755, true);

        file_put_contents(storage_path('app/dusk-scraper/console/.gitignore'), '*
!.gitignore
');
    }
}
