<?php

namespace App\Console\Commands;

use App\Models\Email;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mails:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envio de emails pendientes';

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
        
        DB::beginTransaction();
        try {
            
            
            $emails = Email::where('status',0)->orderBy("id")->get();
            if(!$emails->isEmpty()){
                $this->info('Se va a proceder a realizar el envio de emails pendientes');
                foreach($emails as $email){
                    $email->status = 1;
                    $email->update();
                    $this->info('Asunto: '.$email->asunto." Destinatario: ".$email->destinatario." | Enviado");
                } 
            }else{
                return  $this->info('No hay emails pendientes de enviar');
            }
                       

            DB::commit();
        } catch (\Exception $e) {

            DB::rollback();
            return false;
        }
        return $this->info('Emails enviados correctamente');

    }
}
