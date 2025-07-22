<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Webklex\IMAP\Facades\Client;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\YourImportClass;
use App\Imports\DynamicSheetImport;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use PhpOffice\PhpSpreadsheet\IOFactory;

class EmailImportController extends Controller{
//==============================Start==================//
 public function importExcelFromEmail(){
        try {
            $client = Client::account('default');
            $client->connect();

            $folder = $client->getFolder('INBOX');
            $messages = $folder->messages()->unseen()->get();

            foreach ($messages as $message) {
                foreach ($message->getAttachments() as $attachment) {
                    if (in_array($attachment->getExtension(), ['xlsx', 'xls', 'csv'])) {

                        // Ensure folder exists
                        Storage::makeDirectory('excel');

                        $path = storage_path('app/excel/' . $attachment->name);
                        $attachment->save(storage_path('app/excel'));

                        // Import Excel file
                        Excel::import(new YourImportClass, $path);
                    }
                }

                // Mark the message as read
                $message->setFlag('Seen');
            }

            return response()->json(['success' => true, 'message' => 'Email Excel imported successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
//===========================/End=======================//
}
