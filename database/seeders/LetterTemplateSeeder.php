<?php

namespace Database\Seeders;

use App\Models\LetterTemplate;
use Illuminate\Database\Seeder;

class LetterTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LetterTemplate::create([
            'name' => 'Surat Peringatan 1 (SP1)',
            'code' => 'SP1',
            'content' => '
<p style="text-align: center;"><strong>SURAT PERINGATAN 1</strong></p>
<p style="text-align: center;">No: [LETTER_NUMBER]</p>
<p>&nbsp;</p>
<p>Kepada Yth,<br />Sdr. [RECIPIENT_NAME]<br />di Tempat</p>
<p>Memperhatikan kinerja dan kedisiplinan Saudara dalam beberapa waktu terakhir, khususnya mengenai pelanggaran tata tertib perusahaan berupa:</p>
<p><strong>[REASON]</strong></p>
<p>Maka dengan ini Perusahaan memberikan <strong>SURAT PERINGATAN PERTAMA (SP-1)</strong> kepada Saudara. Kami berharap Saudara dapat memperbaiki kinerja dan tidak mengulangi kesalahan yang sama di kemudian hari.</p>
<p>Surat Peringatan ini berlaku selama 6 (enam) bulan sejak diterbitkan. Apabila dalam kurun waktu tersebut Saudara kembali melakukan pelanggaran, maka Perusahaan akan memberikan sanksi yang lebih berat sesuai peraturan yang berlaku.</p>
<p>Demikian surat peringatan ini dibuat untuk menjadi perhatian.</p>
<p>&nbsp;</p>
<p>[DATE]</p>
<p>Hormat kami,</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p><strong>[CREATOR_NAME]</strong><br />Human Capital Services</p>
            ',
        ]);

        LetterTemplate::create([
            'name' => 'Perjanjian Kerja Waktu Tertentu (PKWT)',
            'code' => 'PKWT',
            'content' => '
<p style="text-align: center;"><strong>PERJANJIAN KERJA WAKTU TERTENTU</strong></p>
<p style="text-align: center;">No: [LETTER_NUMBER]</p>
<p>&nbsp;</p>
<p>Pada hari ini, <strong>[DAY_NAME]</strong> tanggal <strong>[DATE]</strong>, yang bertanda tangan di bawah ini:</p>
<ol>
<li><strong>[COMPANY_NAME]</strong>, berkedudukan di [COMPANY_ADDRESS], dalam hal ini diwakili oleh [DIRECTOR_NAME], selanjutnya disebut <strong>PIHAK PERTAMA</strong>.</li>
<li><strong>[RECIPIENT_NAME]</strong>, bertempat tinggal di [RECIPIENT_ADDRESS], selanjutnya disebut <strong>PIHAK KEDUA</strong>.</li>
</ol>
<p>Kedua belah pihak sepakat untuk mengikatkan diri dalam Perjanjian Kerja Waktu Tertentu dengan ketentuan sebagai berikut:</p>
<p><strong>Pasal 1</strong><br /><strong>JANGKA WAKTU</strong></p>
<p>Perjanjian ini berlaku selama [DURATION] bulan, terhitung mulai tanggal [START_DATE] sampai dengan [END_DATE].</p>
<p>...</p>
<p>Demikian perjanjian ini dibuat dan ditandatangani oleh kedua belah pihak.</p>
<p>&nbsp;</p>
<p>PIHAK PERTAMA,&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;PIHAK KEDUA,</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>________________&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ________________</p>
            ',
        ]);
        
        LetterTemplate::create([
            'name' => 'Surat Keterangan Kerja (Paklaring)',
            'code' => 'SKK',
            'content' => '
<p style="text-align: center;"><strong>SURAT KETERANGAN KERJA</strong></p>
<p style="text-align: center;">No: [LETTER_NUMBER]</p>
<p>&nbsp;</p>
<p>Yang bertanda tangan di bawah ini:</p>
<p>Nama: [CREATOR_NAME]<br />Jabatan: [CREATOR_POSITION]</p>
<p>Menerangkan bahwa:</p>
<p>Nama: [RECIPIENT_NAME]<br />NIK: [RECIPIENT_NIK]<br />Jabatan Terakhir: [RECIPIENT_POSITION]</p>
<p>Telah bekerja pada perusahaan kami sejak tanggal [START_DATE] sampai dengan [END_DATE] dengan dedikasi dan loyalitas yang baik.</p>
<p>Demikian surat keterangan ini dibuat agar dapat dipergunakan sebagaimana mestinya.</p>
<p>&nbsp;</p>
<p>[DATE]</p>
<p>&nbsp;</p>
<p><strong>[CREATOR_NAME]</strong></p>
            ',
        ]);
    }
}
