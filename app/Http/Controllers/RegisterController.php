<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class RegisterController extends Controller
{
    //view regis
    public function register(Request $request)
    {
        $dataEvent = DB::table('event')
            ->where('id_event', $request->query('event'))
            ->first();

        if (!$dataEvent) {
            // Jika $dataEvent kosong (null)
            abort(404);
        }

        $listKategori = DB::table('kategori')->get();

        return view('register', [
            'dataEvent' => $dataEvent,
            'listKategori' => $listKategori,
            'idEvent' => $request->query('event'),
            'jasaLayanan' => $dataEvent->jasa_layanan,
            'kontakInfo' => $dataEvent->kontak_info
        ]);
    }

    //post regis
    public function postregister(Request $request)
    {
        try{

            // Validasi input
            $tmpKategori = explode('|', $request->input('kategori'));
            $idKategori = $tmpKategori[0];
            $hargaKategori = $tmpKategori[1];
            $namaKategori = $tmpKategori[2];

            // Upload foto depan
            $filenameDepan = null;
            if ($request->hasFile('foto_depan')) {
                $file = $request->file('foto_depan');
                $filenameDepan = 'fotodepan_'.$idKategori.'_'.Carbon::now()->addHours(7)->format('YmdHis').'-'.$request->input('nomor_hp').'.jpg';
                $file->move(public_path('img'), $filenameDepan);
            }

            // Upload foto belakang
            // $filenameBelakang = null;
            // if ($request->hasFile('foto_belakang')) {
            //     $file = $request->file('foto_belakang');
            //     $filenameBelakang = 'fotobelakang_'.$idKategori.'_'.Carbon::now()->addHours(7)->format('YmdHis').'-'.$request->input('nomor_hp').'.jpg';
            //     $file->move(public_path('img'), $filenameBelakang);
            // }

            // // Upload foto samping
            // $filenameSamping = null;
            // if ($request->hasFile('foto_samping')) {
            //     $file = $request->file('foto_samping');
            //     $filenameSamping = 'fotosamping_'.$idKategori.'_'.Carbon::now()->addHours(7)->format('YmdHis').'-'.$request->input('nomor_hp').'.jpg';
            //     $file->move(public_path('img'), $filenameSamping);
            // }
            $qtySlot = $request->input('qty_slot');
            $kodeUnik = mt_rand(100, 999);
            $totalBayar = ($hargaKategori * $qtySlot) + $kodeUnik + ($qtySlot *$request->input('jasaLayanan'));
            $idTransaksi = Carbon::now()->addHours(7)->format('dmYHis') . substr($request->input('nomor_hp'), -4);

            // Simpan data registrasi
            DB::table('peserta')->insert([
                'id_transaksi' => $idTransaksi,
                'id_event' => $request->input('idEvent'),
                'nama_lengkap' => $request->input('nama'),
                'kota_asal' => $request->input('kota'),
                'nomor_hp' => $request->input('nomor_hp'),
                'email' => $request->input('email'),
                'nama_team' => $request->input('nama_team'),
                'id_kategori' => $idKategori,
                'foto_depan' => $filenameDepan,
                'foto_belakang' => '-',
                'foto_samping' => '-',
                'kode_unik' => $kodeUnik,
                'total_bayar' => $totalBayar,
                'qty_slot' => $qtySlot,
                'addtime' => Carbon::now()->addHours(7)->format('Y-m-d H:i:s'),
            ]);

            //kirim email
            $dataEvent = DB::table('event')->where('id_event', '=', $request->input('idEvent'))->first();
            $data = [
                'idTransaksi' => $idTransaksi,
                'nama_lengkap' => $request->input('nama'),
                'nama_kategori' => $namaKategori,
                'email' => $request->input('email'),
                'nama_team' => $request->input('nama_team'),
                'nomor_hp' => $request->input('nomor_hp'),
                'qty_slot' => $qtySlot,
                'totalbayar' => $totalBayar,
                'bank' => $dataEvent->nama_bank,
                'namarek' => $dataEvent->nama_rekening,
                'norek' => $dataEvent->nomor_rekening,
            ];
            
            // Konten HTML untuk email
            $bodyEmail = '
            <html>
            <body>
                <h1 style="color: #3490dc;">[DETAIL INVOICE] Terima kasih atas pendaftaran Anda, ' . $data['nama_lengkap'] . '</h1>
                <p>Selamat bergabung dengan kami!</p>
                <p>Berikut adalah informasi pendaftaran Anda:</p>
                
                <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 50%;">
                    <tr>
                        <th style="background-color: #f2f2f2; text-align: left;">Informasi</th>
                        <th style="background-color: #f2f2f2; text-align: left;">Detail</th>
                    </tr>
                    <tr>
                        <td>ID Transaksi</td>
                        <td>' . $data['idTransaksi'] . '</td>
                    </tr>
                    <tr>
                        <td>Nama Peserta</td>
                        <td>' . $data['nama_lengkap'] . '</td>
                    </tr>
                    <tr>
                        <td>Kategori</td>
                        <td>' . $data['nama_kategori'] . '</td>
                    </tr>
                    <tr>
                        <td>Jumlah Slot</td>
                        <td>' . $data['qty_slot'] . '</td>
                    </tr>
                    <tr>
                        <td>Bengkel atau Komunitas</td>
                        <td>' . $data['nama_team'] . '</td>
                    </tr>
                    <tr>
                        <td>Nomor Handphone</td>
                        <td>' . $data['nomor_hp'] . '</td>
                    </tr>
                </table>

                <br>
                <h4 style="color: #28a745;">Informasi Pembayaran:</h4>
                <div>
                <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 50%;">
                    <tr>
                        <td>Biaya Registrasi </td>
                        <td>Rp ' . number_format(($hargaKategori * $qtySlot), 0, ',', '.') . ' &#40; ' .$qtySlot. ' Slot &#41;</td>
                    </tr>
                    <tr>
                        <td>Biaya Layanan</td>
                        <td>Rp ' . number_format(($request->input('jasaLayanan') * $qtySlot), 0, ',', '.') . ' &#40; ' .$qtySlot. ' Slot &#41;</td>
                    </tr>
                    <tr>
                        <td>Kode Unik</td>
                        <td>Rp ' . number_format($kodeUnik, 0, ',', '.') . '</td>
                    </tr>
                    <tr>
                        <td><strong>Total Bayar</strong></td>
                        <td><strong>Rp ' . number_format($data['totalbayar'], 0, ',', '.') . '</strong></td>
                    </tr>
                </table>
                <h5 style="color: #007bff;">Informasi Rekening:</h5>
                <ul style="list-style-type: none; padding: 0;">
                    <li><strong>Bank:</strong> '.$data['bank'].'</li>
                    <li><strong>Nomor Rekening:</strong> '.$data['norek'].'</li>
                    <li><strong>Atas Nama:</strong> '.$data['namarek'].'</li>
                </ul>
                </div>
                <br>
                <p>Silakan hubungi kami jika Anda memiliki pertanyaan lebih lanjut.</p>
                <p>Salam,</p>
                <p><strong>Customer Support</strong></p>
            </body>
            </html>
            ';

            Mail::html($bodyEmail, function ($message) use ($data) {
                $message->to($data['email'], $data['nama_lengkap']);
                $message->subject('Informasi Pembayaran');
            });

            return redirect()->route('statusTransaksi', ['id_transaksi' => $idTransaksi]);

        }catch(\Exception $e){
            Log::error('Error occurred report : ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan : ' . $e->getMessage());
        }
    }

    public function cekstatus()
    {
        try{

            return view('cekstatus');

        }catch(\Exception $e){
            Log::error('Error occurred report : ' . $e->getMessage());
            abort(404);
        }
        
    }

    public function statusTransaksi($id_transaksi)
    {
        try{

            $dataPeserta = DB::table('peserta')
            ->join('kategori', 'peserta.id_kategori', '=', 'kategori.id_kategori')
            ->join('event', 'peserta.id_event', '=', 'event.id_event')
            ->where('id_transaksi', $id_transaksi)
            ->select('peserta.*', 'kategori.*', 'event.*')
            ->first();

            if (!$dataPeserta) {
                abort(404);
            }

            return view('checkout', [
                'email' => $dataPeserta->email,
                'nama_lengkap' => $dataPeserta->nama_lengkap,
                'nama_team' => $dataPeserta->nama_team,
                'kategori' => $dataPeserta->nama_kategori,
                'nomor_hp' => $dataPeserta->nomor_hp,
                'harga_kategori' => $dataPeserta->harga_kategori,
                'jasa_layanan' => $dataPeserta->jasa_layanan,
                'kode_unik' => $dataPeserta->kode_unik,
                'total_bayar' => $dataPeserta->total_bayar,
                'status_pembayaran' => $dataPeserta->status_user,
                'nama_bank' => $dataPeserta->nama_bank,
                'nama_rek' => $dataPeserta->nama_rekening,
                'nomor_rek' => $dataPeserta->nomor_rekening,
                'idTransaksi' => $dataPeserta->id_transaksi,
                'qty_slot' => $dataPeserta->qty_slot,
            ]);

        }catch(\Exception $e){
            Log::error('Error occurred report : ' . $e->getMessage());
            abort(404);
        }
    }

    public function listregistration()
    {
        try{

            if (!session()->has('user_id')) {
                return redirect('/');
            }

            $listEvent = DB::table('event')->get();

            return view('listregistrationV2', [
                'event' => $listEvent
            ]);
        }catch(\Exception $e){
            Log::error('Error occurred report : ' . $e->getMessage());
            return view('listregistrationV2', ['error' => 'Terjadi kesalahan : ' . $e->getMessage()]);
        }
    }

    public function reportregistration(Request $request)
    {
        try{

            if (!session()->has('user_id')) {
                return redirect('/');
            }

            list($id_event, $title_event) = explode('|', $request->input('event'));

            $listEvent = DB::table('event')->get();

            if($request->action == "report"){

                $listPeserta = DB::table('peserta')
                ->join('event', 'peserta.id_event', '=', 'event.id_event')
                ->join('kategori', 'peserta.id_kategori', '=', 'kategori.id_kategori')
                ->where('peserta.is_delete', '0')
                ->where('peserta.id_event', '=', $id_event)
                ->select('peserta.*', 'event.title_event', 'kategori.*')
                ->get();

                return view('listregistrationV2', [
                    'data' => $listPeserta,
                    'event' => $listEvent
                ]);

            }else{

                $listData = DB::table('peserta')
                ->join('event', 'peserta.id_event', '=', 'event.id_event')
                ->join('kategori', 'peserta.id_kategori', '=', 'kategori.id_kategori')
                ->where('peserta.is_delete', '0')
                ->where('peserta.id_event', '=', $id_event)
                ->orderBy('peserta.addtime', 'asc')
                ->select(
                    'peserta.*',
                    'kategori.nama_kategori as kategori',
                    'event.title_event as nama_event'
                )
                ->get();

                if ($listData->isEmpty()) {
                    return back()->with('error', 'Tidak ada data untuk event yang dipilih.');
                }

                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                //header kolom
                $sheet->setCellValue('A1', 'Laporan Pendaftaran');
                $sheet->mergeCells('A1:H1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $sheet->setCellValue('A2', '');
                $sheet->mergeCells('A2:H2');
                $sheet->getStyle('A2')->getFont()->setBold(true);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $sheet->setCellValue('A3', 'Event: ' . $title_event);
                $sheet->mergeCells('A3:H3');
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $sheet->setCellValue('A4', 'Tanggal Generate: ' . date('d-m-Y H:i:s', strtotime('+7 hours')));
                $sheet->mergeCells('A4:H4');
                $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $startRow = 6;

                // Header kolom
                $sheet->setCellValue("A$startRow", 'No');
                $sheet->setCellValue("B$startRow", 'ID Transaksi');
                $sheet->setCellValue("C$startRow", 'Nama Lengkap');
                $sheet->setCellValue("D$startRow", 'Bengkel atau Komunitas');
                $sheet->setCellValue("E$startRow", 'Kategori');
                $sheet->setCellValue("F$startRow", 'Jumlah Slot');
                $sheet->setCellValue("G$startRow", 'Nomor HP');
                $sheet->setCellValue("H$startRow", 'Kota');
                $sheet->setCellValue("I$startRow", 'Status');

                // Styling header kolom
                $sheet->getStyle("A$startRow:I$startRow")->getFont()->setBold(true);
                $sheet->getStyle("A$startRow:I$startRow")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("A$startRow:I$startRow")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                //data ke sheet
                $row = $startRow + 1;
                $no = 1;
                foreach ($listData as $data) {
                    $sheet->setCellValue("A$row", $no++);
                    $sheet->setCellValue("B$row", "'{$data->id_transaksi}'");
                    $sheet->setCellValue("C$row", $data->nama_lengkap);
                    $sheet->setCellValue("D$row", $data->nama_team);
                    $sheet->setCellValue("E$row", $data->kategori);
                    $sheet->setCellValue("F$row", $data->qty_slot);
                    $sheet->setCellValue("G$row", $data->nomor_hp);
                    $sheet->setCellValue("H$row", $data->kota_asal);
                    $sheet->setCellValue("I$row", $data->status_user);
                    $row++;
                }

                // Auto-size kolom
                foreach (range('A', 'I') as $columnID) {
                    $sheet->getColumnDimension($columnID)->setAutoSize(true);
                }

                $fileName = 'LaporanPendaftaranEvent_' . $title_event . '_' . date('dmYHis', strtotime('+7 hours')) . '.xlsx';
                $filePath = public_path($fileName);
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $writer->save($filePath);

                return response()->download($filePath)->deleteFileAfterSend(true);
            }
        }catch(\Exception $e){
            Log::error('Error occurred report : ' . $e->getMessage());
            return view('listregistrationV2', ['error' => 'Terjadi kesalahan : ' . $e->getMessage()]);
        }
    }

    public function postapproveuser(Request $request)
    {
        try{

            if (!session()->has('user_id')) {
                return redirect('/');
            }

            if($request->input('proses') == "approve"){

                //kirim Email
                $data = [
                    'rowid' => $request->input('rowid'),
                    'idTransaksi' => $request->input('idTransaksi'),
                    'nama_lengkap' => $request->input('nama'),
                    'nama_kategori' => $request->input('kategori'),
                    'email' => $request->input('email'),
                    'nama_team' => $request->input('nama_team'),
                    'nomor_hp' => $request->input('nomor_hp'),
                    'qty_slot' => $request->input('qty_slot'),
                ];
                
                // Konten HTML untuk email
                $bodyEmail = '
                    <html>
                    <body>
                        <h1 style="color: #3490dc;">[SUKSES] Terima kasih atas pendaftaran Anda, ' . $data['nama_lengkap'] . '</h1>
                        <p>Selamat bergabung dengan kami!</p>
                        <p>Berikut adalah informasi pendaftaran Anda:</p>
                        
                        <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 50%;">
                            <tr>
                                <th style="background-color: #f2f2f2; text-align: left;">Informasi</th>
                                <th style="background-color: #f2f2f2; text-align: left;">Detail</th>
                            </tr>
                            <tr>
                                <td>ID Transaksi</td>
                                <td>' . $data['idTransaksi'] . '</td>
                            </tr>
                            <tr>
                                <td>Nama Peserta</td>
                                <td>' . $data['nama_lengkap'] . '</td>
                            </tr>
                            <tr>
                                <td>Kategori</td>
                                <td>' . $data['nama_kategori'] . '</td>
                            </tr>
                            <tr>
                                <td>Jumlah Slot</td>
                                <td>' . $data['qty_slot'] . '</td>
                            </tr>
                            <tr>
                                <td>Bengkel atau Komunitas</td>
                                <td>' . $data['nama_team'] . '</td>
                            </tr>
                            <tr>
                                <td>Nomor Handphone</td>
                                <td>' . $data['nomor_hp'] . '</td>
                            </tr>
                            <tr>
                                <td>Status Pembayaran</td>
                                <td>LUNAS</td>
                            </tr>
                        </table>
                
                        <br>
                        <p>Silakan hubungi kami jika Anda memiliki pertanyaan lebih lanjut.</p>
                        <p>Salam,</p>
                        <p><strong>Customer Support</strong></p>
                    </body>
                    </html>
                ';

                Mail::html($bodyEmail, function ($message) use ($data) {
                    $message->to($data['email'], $data['nama_lengkap']);
                    $message->subject('Registrasi Berhasil');
                });
                
                DB::table('peserta')
                    ->where('id_peserta', $request->input('rowid'))
                    ->update([
                        'status_user' => 'APPROVED',
                        'approved_time' => Carbon::now()->addHours(7)->format('Y-m-d H:i:s'),
                    ]);

                return redirect()->back()->with('success', 'Approve success!');
            }
            else if($request->input('proses') == "CONFIRMATION"){

                DB::table('peserta')
                    ->where('id_peserta', $request->input('rowid'))
                    ->update([
                        'status_user' => 'CONFIRMATION',
                    ]);

                // Kembalikan respons JSON
                return response()->json([
                    'message' => 'Berhasil dikonfirmasi.',
                    'status' => 'success'
                ]);
            }
            else{
                //delete
                //DB::table('peserta')->where('rowid', $request->input('rowid'))->delete();

                DB::table('peserta')
                    ->where('id_peserta', $request->input('rowid'))
                    ->update([
                        'is_delete' => '1',
                    ]);

                return redirect()->back()->with('success', 'Delete peserta success!');
            }
        }catch(\Exception $e){
            Log::error('Error occurred : ' . $e->getMessage());
            return redirect()->back()->with('success', 'Error : ' . $e->getMessage());
        }
    }

    public function postbuktitransfer(Request $request)
    {
        try{
            
            // Upload foto
            $filename = null;
            if ($request->hasFile('bukti_transfer')) {
                $file = $request->file('bukti_transfer');
                $filename = 'buktitransfer_'.date('YmdHis').'_'.$request->input('nohp').'.jpg';
                $file->move(public_path('invoice'), $filename);
            }

            $lastRow = DB::table('peserta')
                ->where('nomor_hp', $request->input('nohp'))
                ->where('email', $request->input('email'))
                ->orderBy('id_peserta', 'desc')
                ->first();

            if ($lastRow) {
                DB::table('peserta')
                    ->where('id_peserta', $lastRow->id_peserta)
                    ->update(['foto_bukti_trf' => $filename]);
            }

            return view('success');

        }catch(\Exception $e){
            Log::error('Error occurred report : ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan : ' . $e->getMessage());
        }
    }

}
