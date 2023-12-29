<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Intervention\Image\ImageManagerStatic as Image;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\File;
use App\Models\LiaisonShopArticlesBill;
use App\Models\Shop_article;
use App\Models\shop_article_0;
require_once(app_path().'/fonction.php');




class generatePDF extends Controller
{
    public function generatePDFreduction_FiscaleOutput($id)
    {
        // Récupération des informations de facture
        $billsQuery = DB::table('bills')
            ->join('users', 'bills.user_id', '=', 'users.user_id')
            ->join('bills_status', 'bills.status', '=', 'bills_status.id')
            ->join('bills_payment_method', 'bills.payment_method', '=', 'bills_payment_method.id')
            ->where('bills.id', $id)
            ->select('bills.*','bills_payment_method.payment_method as method_payment', 'bills_status.row_color', 'bills_status.status as bill_status','users.name', 'users.lastname', 'users.email', 'users.phone', 'users.address', 'users.city', 'users.zip', 'users.country','users.birthdate');
        $oldBillsQuery = DB::table('old_bills')
            ->join('users', 'old_bills.user_id', '=', 'users.user_id')
            ->join('bills_status', 'old_bills.status', '=', 'bills_status.id')
            ->join('bills_payment_method', 'old_bills.payment_method', '=', 'bills_payment_method.id')
            ->where('old_bills.id', $id)
            ->select('old_bills.*','bills_payment_method.payment_method as method_payment', 'bills_status.row_color', 'bills_status.status as bill_status','users.name', 'users.lastname', 'users.email', 'users.phone', 'users.address', 'users.city', 'users.zip', 'users.country','users.birthdate');
        $bill = $billsQuery->union($oldBillsQuery)->first();
        
        // Chargement de l'image de fond
        $image = Image::make(public_path('assets/images/Page-CERFA-1.png'));
        $image->resize(700, 1000); 

        $image2 = Image::make(public_path('assets/images/Page-CERFA-2.png'));
        $image2->resize(700, 1000);

		$image->text(10000+$bill->id, 600, 70, function($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(12);
            $font->color('#000000');
        });

        $image2->text($bill->name, 100, 86, function($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(12);
            $font->color('#000000');
        });
        
    
        $image2->text($bill->lastname, 419, 73+13, function($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(12);
            $font->color('#000000');
        });
        

        $image2->text($bill->address, 119, 119+13, function($font) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(12);
        $font->color('#000000');
        });
        $image2->text($bill->zip, 122, 142+13, function($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(12);
            $font->color('#000000');
        });
        $image2->text($bill->city, 285, 141+13, function($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(12);
            $font->color('#000000');
        });

        $image2->text(date('d', strtotime($bill->date_bill)), 228, 290+13, function($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(12);
            $font->color('#000000');
        });
        $image2->text(date('m', strtotime($bill->date_bill)), 266, 290+13, function($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(12);
            $font->color('#000000');
        });
        $image2->text(date('Y', strtotime($bill->date_bill)), 332, 290+13, function($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(12);
            $font->color('#000000');
        });

        $image2->text(\Carbon\Carbon::parse($bill->date_bill)->format('d-m-Y'), 490, 793, function($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(12);
            $font->color('#000000');
        });

        if ($bill->method_payment == 'Virement' || $bill->method_payment == 'Carte Bancaire') {
            $image2->text('x', 388, 559, function($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(12);
                $font->color('#000000');
            });
        } else if ($bill->method_payment == 'Chèque') {
            $image2->text('x', 193, 559, function($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(12);
                $font->color('#000000');
            });
        } else if ($bill->method_payment == 'Espèces') {
            $image2->text('x', 50, 559, function($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(12);
                $font->color('#000000');
            });
        }

         // Conversion de l'image modifiée en PDF
         $pdf1 = PDF::loadHTML('<img src="data:image/' . $image->mime . ';base64,' . base64_encode($image->encode()) . '">');
         $pdf2 = PDF::loadHTML('<img src="data:image/' . $image2->mime . ';base64,' . base64_encode($image2->encode()) . '">');
         
         // Save the PDFs to temporary files
         $tempFile1 = tempnam(sys_get_temp_dir(), 'pdf');
         $tempFile2 = tempnam(sys_get_temp_dir(), 'pdf');
         $pdf1->save($tempFile1);
         $pdf2->save($tempFile2);
         
         // Merge the PDFs into a single PDF
         $pdf = new Fpdi();
         $pageCount1 = $pdf->setSourceFile($tempFile1);
         for ($pageIndex = 1; $pageIndex <= $pageCount1; $pageIndex++) {
             $pdf->AddPage();
             $templateId = $pdf->importPage($pageIndex);
             $pdf->useTemplate($templateId);
         }
         
         $pageCount2 = $pdf->setSourceFile($tempFile2);
         for ($pageIndex = 1; $pageIndex <= $pageCount2; $pageIndex++) {
             $pdf->AddPage();
             $templateId = $pdf->importPage($pageIndex);
             $pdf->useTemplate($templateId);
         }
         
         // Send the merged PDF to the browser for download
         return $pdf->Output('Facture-'.$bill->id.'.pdf', 'S');
    }

    public function generatePDFreduction_Fiscale($id)
    {
        // Récupération des informations de facture
        $billsQuery = DB::table('bills')
            ->join('users', 'bills.user_id', '=', 'users.user_id')
            ->join('bills_status', 'bills.status', '=', 'bills_status.id')
            ->join('bills_payment_method', 'bills.payment_method', '=', 'bills_payment_method.id')
            ->where('bills.id', $id)
            ->select('bills.*','bills_payment_method.payment_method as method_payment', 'bills_status.row_color', 'bills_status.status as bill_status','users.name', 'users.lastname', 'users.email', 'users.phone', 'users.address', 'users.city', 'users.zip', 'users.country','users.birthdate');
        $oldBillsQuery = DB::table('old_bills')
            ->join('users', 'old_bills.user_id', '=', 'users.user_id')
            ->join('bills_status', 'old_bills.status', '=', 'bills_status.id')
            ->join('bills_payment_method', 'old_bills.payment_method', '=', 'bills_payment_method.id')
            ->where('old_bills.id', $id)
            ->select('old_bills.*','bills_payment_method.payment_method as method_payment', 'bills_status.row_color', 'bills_status.status as bill_status','users.name', 'users.lastname', 'users.email', 'users.phone', 'users.address', 'users.city', 'users.zip', 'users.country','users.birthdate');
        $bill = $billsQuery->union($oldBillsQuery)->first();
        

        // Récupération des produits associés à la facture
$billProducts = LiaisonShopArticlesBill::where('bill_id', $bill->id)->get();

$versement = 0;

foreach ($billProducts as $billProduct) {
    $article = Shop_article::find($billProduct->id_shop_article);
    
    if ($article !== null) {  // Check if $article is not null
        if ($article->type_article == 0) {
            $article0 = Shop_article_0::find($billProduct->id_shop_article);
            if ($article0 !== null) {
                $versement += $article0->prix_adhesion * $billProduct->quantity;
            }
        } else if ($article->afiscale == 1) {
            // Pour les autres types d'articles, on vérifie si afiscale est égal à 1
            $versement += $article->price * $billProduct->quantity;
        }
    }
}

     
$versement = floor($versement);

        // Chargement de l'image de fond
        $image = Image::make(public_path('assets/images/Page-CERFA-1.png'));
        $image->resize(700, 1000); 

        $image2 = Image::make(public_path('assets/images/Page-CERFA-2.png'));
        $image2->resize(700, 1000);

        $image->text(10000+$bill->id, 600, 70, function($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(12);
            $font->color('#000000');
        });

        $image2->text($bill->name, 100, 86, function($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(12);
            $font->color('#000000');
        });   
    
        $image2->text($bill->lastname, 419, 73+13, function($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(12);
            $font->color('#000000');
        });
        

        $image2->text($bill->address, 119, 119+13, function($font) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(12);
        $font->color('#000000');
        });
        $image2->text($bill->zip, 122, 142+13, function($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(12);
            $font->color('#000000');
        });
        $image2->text($bill->city, 285, 141+13, function($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(12);
            $font->color('#000000');
        });


        $image2->text($versement, 281, 237, function($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(10); 
            $font->color('#000000'); 
        });

        $versementEnLettres = chiffreEnLettre($versement) . ' euros';
        $image2->text($versementEnLettres, 191, 271, function($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(10);
            $font->color('#000000');
        });


        $image2->text(date('d', strtotime($bill->date_bill)), 228, 290+13, function($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(12);
            $font->color('#000000');
        });
        $image2->text(date('m', strtotime($bill->date_bill)), 266, 290+13, function($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(12);
            $font->color('#000000');
        });
        $image2->text(date('Y', strtotime($bill->date_bill)), 332, 290+13, function($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(12);
            $font->color('#000000');
        });

        $image2->text(\Carbon\Carbon::parse($bill->date_bill)->format('d-m-Y'), 490, 793, function($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(12);
            $font->color('#000000');
        });

        if ($bill->method_payment == 'Virement' || $bill->method_payment == 'Carte Bancaire') {
            $image2->text('x', 388, 559, function($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(12);
                $font->color('#000000');
            });
        } else if ($bill->method_payment == 'Chèque') {
            $image2->text('x', 193, 559, function($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(12);
                $font->color('#000000');
            });
        } else if ($bill->method_payment == 'Espèces') {
            $image2->text('x', 50, 559, function($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(12);
                $font->color('#000000');
            });
        }

         // Conversion de l'image modifiée en PDF
         $pdf1 = PDF::loadHTML('<img src="data:image/' . $image->mime . ';base64,' . base64_encode($image->encode()) . '">');
         $pdf2 = PDF::loadHTML('<img src="data:image/' . $image2->mime . ';base64,' . base64_encode($image2->encode()) . '">');
         
         // Save the PDFs to temporary files
         $tempFile1 = tempnam(sys_get_temp_dir(), 'pdf');
         $tempFile2 = tempnam(sys_get_temp_dir(), 'pdf');
         $pdf1->save($tempFile1);
         $pdf2->save($tempFile2);
         
         // Merge the PDFs into a single PDF
         $pdf = new Fpdi();
         $pageCount1 = $pdf->setSourceFile($tempFile1);
         for ($pageIndex = 1; $pageIndex <= $pageCount1; $pageIndex++) {
             $pdf->AddPage();
             $templateId = $pdf->importPage($pageIndex);
             $pdf->useTemplate($templateId);
         }
         
         $pageCount2 = $pdf->setSourceFile($tempFile2);
         for ($pageIndex = 1; $pageIndex <= $pageCount2; $pageIndex++) {
             $pdf->AddPage();
             $templateId = $pdf->importPage($pageIndex);
             $pdf->useTemplate($templateId);
         }
         
         // Send the merged PDF to the browser for download
         $pdf->Output('RedFiscale-'.$bill->id.'.pdf', 'D');
    }

    
    public function generatePDFfacture($id)
{
    // Récupération des informations de facture
    $billsQuery = DB::table('bills')
            ->join('users', 'bills.user_id', '=', 'users.user_id')
            ->join('bills_status', 'bills.status', '=', 'bills_status.id')
            ->join('bills_payment_method', 'bills.payment_method', '=', 'bills_payment_method.id')
            ->where('bills.id', $id)
            ->select('bills.*','bills_payment_method.payment_method as method_payment', 'bills_status.row_color', 'bills_status.status as bill_status','users.name', 'users.lastname', 'users.email', 'users.phone', 'users.address', 'users.city', 'users.zip', 'users.country','users.birthdate');
        $oldBillsQuery = DB::table('old_bills')
            ->join('users', 'old_bills.user_id', '=', 'users.user_id')
            ->join('bills_status', 'old_bills.status', '=', 'bills_status.id')
            ->join('bills_payment_method', 'old_bills.payment_method', '=', 'bills_payment_method.id')
            ->where('old_bills.id', $id)
            ->select('old_bills.*','bills_payment_method.payment_method as method_payment', 'bills_status.row_color', 'bills_status.status as bill_status','users.name', 'users.lastname', 'users.email', 'users.phone', 'users.address', 'users.city', 'users.zip', 'users.country','users.birthdate');
        $bill = $billsQuery->union($oldBillsQuery)->first();

    // Récupération des informations de produits
    $shop = DB::table('liaison_shop_articles_bills')
    ->leftJoin('declinaisons', 'declinaisons.id', '=', 'liaison_shop_articles_bills.declinaison') // Left join with the declinaisons table
        ->select('quantity', 'ttc', 'sub_total', 'designation', 'addressee', 'shop_article.image', 'liaison_shop_articles_bills.id_liaison','liaison_shop_articles_bills.href_product', 'declinaisons.libelle as declinaison_libelle')
        ->join('bills', 'bills.id', '=', 'liaison_shop_articles_bills.bill_id')
        ->join('shop_article', 'shop_article.id_shop_article', '=', 'liaison_shop_articles_bills.id_shop_article')
        ->where('bills.id', '=', $id)
        ->get();

        if ($shop->count() <= 7) {

    // Chargement de l'image de fond
    $image = Image::make(public_path('assets/images/Facture_page.png'));
    $image->resize(700, 1000); // Replace 800 and 1200 with the desired width and height

    $now = now();
    $image->text(\Carbon\Carbon::parse($bill->date_bill)->format('d-m-Y'), 449, 234, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(14);
        $font->color('#000000');
        $font->align('left');
        $font->valign('top');
    });

    // Ajout de la référence de la facture sur l'image
    $image->text($bill->ref, 449, 258, function($font) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(18);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $image->text($bill->name.' '.$bill->lastname, 369, 139, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(12);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
    
    $address = $bill->address;
    $image->text($address, 369, 150, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(12);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $image->text($bill->zip, 409, 179, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(12);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $image->text($bill->city, 378, 193, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(12);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $image->text($bill->method_payment, 420, 829, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(16);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $payment_total_amount = number_format($bill->payment_total_amount, 2, ',', ' ')  . " €";
    $image->text(	$payment_total_amount, 581, 755, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(12);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $image->text("En ".$bill->number." fois", 108, 829, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(16);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    // Coordonnées de la première ligne de produit
    $x = 35;
    $y = 334;
    $fontSize = 10;
    // Boucle sur les produits
    foreach ($shop as $product) {
    // Imprimer la référence
    $image->text($product->href_product, $x, $y, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(6);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });


    $imagePath = public_path(ltrim($product->image, '/'));

    if (File::exists($imagePath)) {
        $shopImage = Image::make($imagePath)->resize(47, 46);
        $image->insert($shopImage, 'top-left', 108, $y + (-15));
    }
    
     
    $title = $product->designation;
if (!is_null($product->declinaison_libelle)) {
    $title .= " [" . $product->declinaison_libelle . "]"; 
}

$titleLines = wordwrap($title, 40, "\n", true);
$titleLinesArray = explode("\n", $titleLines);

foreach ($titleLinesArray as $i => $titleLine) {
    $image->text($titleLine, $x + 126, $y - 4 + ($i * 12), function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
}



    $fontSizeSmaller = $fontSize - 3;  
$addresseeLines = wordwrap($product->addressee, 40, "\n", true);
$addresseeLinesArray = explode("\n", $addresseeLines);

foreach ($addresseeLinesArray as $i => $addresseeLine) {
    $image->text($addresseeLine, $x + 126, $y + (($i + count($titleLinesArray)) * 12), function($font) use ($fontSizeSmaller) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSizeSmaller);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
}


    // Imprimer la quantité
    


    $image->text($product->quantity, $x + 355, $y, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    // Imprimer le prix unitaire
    $ttc = number_format($product->ttc, 2, ',', ' ')  . " €";
    $image->text($ttc, $x + 428, $y, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    // Imprimer le prix total
    $ttl = number_format($product->sub_total, 2, ',', ' ')  . " €";
    $image->text($ttl, $x + 533, $y, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    // Ajouter l'espacement entre les produits
    $y += 54;
}
    // Conversion de l'image modifiée en PDF
    $pdf = PDF::loadHTML('<img src="data:image/' . $image->mime . ';base64,' . base64_encode($image->encode()) . '">');
    return $pdf->download('Facture-'.$bill->id.'.pdf');}

    elseif ($shop->count() > 7 && $shop->count() < 23) {
        $shop = $shop->toArray();
        $firstGroup = array_slice($shop, 0, 11);
        $secondGroup = array_slice($shop, 11);
        $image = Image::make(public_path('assets/images/FactureMulti_Page1.png'));
        $image->resize(700, 1000);
        $image2 = Image::make(public_path('assets/images/FactureMulti_Page3.png'));
        $image2->resize(700, 1000);

        
    $now = now();
    $image->text(\Carbon\Carbon::parse($bill->date_bill)->format('d-m-Y'), 449, 234, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(14);
        $font->color('#000000');
        $font->align('left');
        $font->valign('top');
    });

    // Ajout de la référence de la facture sur l'image
    $image->text($bill->ref, 449, 258, function($font) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(18);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $image->text($bill->name.' '.$bill->lastname, 369, 139, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(12);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
    
    $address = $bill->address;
    $image->text($address, 369, 150, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(12);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $image->text($bill->zip, 409, 179, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(12);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $image->text($bill->city, 378, 193, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(12);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $x = 35;
    $y = 334;
    $fontSize = 10;
    // Boucle sur les produits
    foreach ($firstGroup as $product) {

    // Imprimer la référence
    $image->text($product->href_product, $x, $y, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(6);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $imagePath = public_path(ltrim($product->image, '/'));

    if (File::exists($imagePath)) {
        $shopImage = Image::make($imagePath)->resize(47, 46);
        $image->insert($shopImage, 'top-left', 108, $y + (-15));
    }
     
    
    $title = $product->designation;
if (!is_null($product->declinaison_libelle)) {
    $title .= " [" . $product->declinaison_libelle . "]"; 
}
$titleLines = wordwrap($title, 40, "\n", true);
$titleLinesArray = explode("\n", $titleLines);

foreach ($titleLinesArray as $i => $titleLine) {
    $image->text($titleLine, $x + 126, $y - 4 + ($i * 12), function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
}



    $fontSizeSmaller = $fontSize - 3;  
    $addresseeLines = wordwrap($product->addressee, 40, "\n", true);
    $addresseeLinesArray = explode("\n", $addresseeLines);
    
    foreach ($addresseeLinesArray as $i => $addresseeLine) {
        $image->text($addresseeLine, $x + 126, $y + (($i + count($titleLinesArray)) * 12), function($font) use ($fontSizeSmaller) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size($fontSizeSmaller);
            $font->color('#00000');
            $font->align('left');
            $font->valign('top');
        });
    }
    

    // Imprimer la quantité
    



    $image->text($product->quantity, $x + 355, $y, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    // Imprimer le prix unitaire
    $ttc = number_format($product->ttc, 2, ',', ' ')  . " €";
    $image->text($ttc, $x + 428, $y, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    // Imprimer le prix total
    $ttl = number_format($product->sub_total, 2, ',', ' ')  . " €";
    $image->text($ttl, $x + 533, $y, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    // Ajouter l'espacement entre les produits
    $y += 54;
}

$y2 = 111;

foreach ($secondGroup as $product) {
    
    // Imprimer la référence
    $image2->text($product->href_product, $x, $y2, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(6);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $imagePath = public_path(ltrim($product->image, '/'));

    if (File::exists($imagePath)) {
        $shopimage2 = Image::make($imagePath)->resize(47, 46);
     $image2->insert($shopimage2, 'top-left', 108, $y2 + (-15)); 
    }
    
    
    $title = $product->designation;
if (!is_null($product->declinaison_libelle)) {
    $title .= " [" . $product->declinaison_libelle . "]"; 
}

$titleLines = wordwrap($title, 40, "\n", true);
$titleLinesArray = explode("\n", $titleLines);

foreach ($titleLinesArray as $i => $titleLine) {
    $image2->text($titleLine, $x + 126, $y2 - 4 + ($i * 12), function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
}


    $fontSizeSmaller = $fontSize - 3;  
    $addresseeLines = wordwrap($product->addressee, 40, "\n", true);
    $addresseeLinesArray = explode("\n", $addresseeLines);
    
    foreach ($addresseeLinesArray as $i => $addresseeLine) {
        $image2->text($addresseeLine, $x + 126, $y - 4 + 12+ (($i + count($titleLinesArray)) * 12), function($font) use ($fontSizeSmaller) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size($fontSizeSmaller);
            $font->color('#00000');
            $font->align('left');
            $font->valign('top');
        });
    }
    
    // Imprimer la quantité
    



    $image2->text($product->quantity, $x + 355, $y2, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    // Imprimer le prix unitaire
    $ttc = number_format($product->ttc, 2, ',', ' ')  . " €";
    $image2->text($ttc, $x + 428, $y2, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    // Imprimer le prix total
    $ttl = number_format($product->sub_total, 2, ',', ' ')  . " €";
    $image2->text($ttl, $x + 533, $y2, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $y2 += 54;
}

$image2->text($bill->method_payment, 420, 829, function($font)  {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size(16);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

$payment_total_amount = number_format($bill->payment_total_amount, 2, ',', ' ')  . " €";
$image2->text(	$payment_total_amount, 581, 755, function($font)  {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size(12);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});
$image2->text("En ".$bill->number." fois", 108, 755, function($font)  {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size(16);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});



// Convert the images to PDFs
$pdf1 = PDF::loadHTML('<img src="data:image/' . $image->mime . ';base64,' . base64_encode($image->encode()) . '">');
$pdf2 = PDF::loadHTML('<img src="data:image/' . $image2->mime . ';base64,' . base64_encode($image2->encode()) . '">');

// Save the PDFs to temporary files
$tempFile1 = tempnam(sys_get_temp_dir(), 'pdf');
$tempFile2 = tempnam(sys_get_temp_dir(), 'pdf');
$pdf1->save($tempFile1);
$pdf2->save($tempFile2);

// Merge the PDFs into a single PDF
$pdf = new Fpdi();
$pageCount1 = $pdf->setSourceFile($tempFile1);
for ($pageIndex = 1; $pageIndex <= $pageCount1; $pageIndex++) {
    $pdf->AddPage();
    $templateId = $pdf->importPage($pageIndex);
    $pdf->useTemplate($templateId);
}
$pageCount2 = $pdf->setSourceFile($tempFile2);
for ($pageIndex = 1; $pageIndex <= $pageCount2; $pageIndex++) {
    $pdf->AddPage();
    $templateId = $pdf->importPage($pageIndex);
    $pdf->useTemplate($templateId);
}

// Send the merged PDF to the browser for download
$pdf->Output('Facture-'.$bill->id.'.pdf', 'D');



}else{$shop = $shop->toArray();
    $firstGroup = array_slice($shop, 0, 11);
    $thirdGroup = array_slice($shop, 11, 15);
    $secondGroup = array_slice($shop, 26);
    $image = Image::make(public_path('assets/images/FactureMulti_Page1.png'));
    $image->resize(700, 1000);
    $image2 = Image::make(public_path('assets/images/FactureMulti_Page3.png'));
    $image2->resize(700, 1000);
    $image3 = Image::make(public_path('assets/images/FactureMulti_Page2.png'));
    $image3->resize(700, 1000);

    
$now = now();
$image->text(\Carbon\Carbon::parse($bill->date_bill)->format('d-m-Y'), 449, 234, function($font)  {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size(14);
    $font->color('#000000');
    $font->align('left');
    $font->valign('top');
});

// Ajout de la référence de la facture sur l'image
$image->text($bill->ref, 449, 258, function($font) {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size(18);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

$image->text($bill->name.' '.$bill->lastname, 369, 139, function($font)  {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size(12);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

$address = $bill->address;
$image->text($address, 369, 150, function($font)  {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size(12);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

$image->text($bill->zip, 409, 179, function($font)  {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size(12);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

$image->text($bill->city, 378, 193, function($font)  {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size(12);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

$x = 35;
$y = 334;
$fontSize = 10;
// Boucle sur les produits
foreach ($firstGroup as $product) {

// Imprimer la référence
$image->text($product->href_product, $x, $y, function($font) use ($fontSize) {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size(6);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

$imagePath = public_path(ltrim($product->image, '/'));
if (File::exists($imagePath)) {
    $shopImage = Image::make($imagePath)->resize(47, 46);
$image->insert($shopImage, 'top-left', 108, $y + (-15)); 
}


$title = $product->designation;
if (!is_null($product->declinaison_libelle)) {
    $title .= " [" . $product->declinaison_libelle . "]"; 
}

$titleLines = wordwrap($title, 40, "\n", true);
$titleLinesArray = explode("\n", $titleLines);

foreach ($titleLinesArray as $i => $titleLine) {
    $image->text($titleLine, $x + 126, $y - 4 + ($i * 12), function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
}

$fontSizeSmaller = $fontSize - 3;  
$addresseeLines = wordwrap($product->addressee, 40, "\n", true);
$addresseeLinesArray = explode("\n", $addresseeLines);

foreach ($addresseeLinesArray as $i => $addresseeLine) {
    $image->text($addresseeLine, $x + 126, $y + (($i + count($titleLinesArray)) * 12), function($font) use ($fontSizeSmaller) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSizeSmaller);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
}


// Imprimer la quantité




$image->text($product->quantity, $x + 355, $y, function($font) use ($fontSize) {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size($fontSize);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

// Imprimer le prix unitaire
$ttc = number_format($product->ttc, 2, ',', ' ')  . " €";
$image->text($ttc, $x + 428, $y, function($font) use ($fontSize) {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size($fontSize);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

// Imprimer le prix total
$ttl = number_format($product->sub_total, 2, ',', ' ')  . " €";
$image->text($ttl, $x + 533, $y, function($font) use ($fontSize) {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size($fontSize);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

// Ajouter l'espacement entre les produits
$y += 54;
}

$y2 = 111;
foreach ($thirdGroup as $product) {

    // Imprimer la référence
    $image3->text($product->href_product, $x, $y2, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(6);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
    
    $imagePath = public_path(ltrim($product->image, '/'));
    if (File::exists($imagePath)) {
        $shopimage3 = Image::make($imagePath)->resize(47, 46);
    $image3->insert($shopimage3, 'top-left', 108, $y2 + (-15)); 
    }
    
    
    $title = $product->designation;
    if (!is_null($product->declinaison_libelle)) {
        $title .= " [" . $product->declinaison_libelle . "]"; // Appending the declinaison libelle in square brackets
    }
    
    $titleLines = wordwrap($title, 40, "\n", true);
    $titleLinesArray = explode("\n", $titleLines);
    
    foreach ($titleLinesArray as $i => $titleLine) {
        $image3->text($titleLine, $x + 126, $y2 - 4 + ($i * 12), function($font) use ($fontSize) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size($fontSize);
            $font->color('#00000');
            $font->align('left');
            $font->valign('top');
        });
    }
    
    $fontSizeSmaller = $fontSize - 3;  
    $addresseeLines = wordwrap($product->addressee, 40, "\n", true);
    $addresseeLinesArray = explode("\n", $addresseeLines);
    
    foreach ($addresseeLinesArray as $i => $addresseeLine) {
        $image3->text($addresseeLine, $x + 126, $y - 4 + 12+ (($i + count($titleLinesArray)) * 12), function($font) use ($fontSizeSmaller) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size($fontSizeSmaller);
            $font->color('#00000');
            $font->align('left');
            $font->valign('top');
        });
    }
    
    
    // Imprimer la quantité
    
    
    
    $image3->text($product->quantity, $x + 355, $y2, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
    
    // Imprimer le prix unitaire
    $ttc = number_format($product->ttc, 2, ',', ' ')  . " €";
    $image3->text($ttc, $x + 428, $y2, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
    
    // Imprimer le prix total
    $ttl = number_format($product->sub_total, 2, ',', ' ')  . " €";
    $image3->text($ttl, $x + 533, $y2, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
    
    $y2 += 54;
    }
    
    
foreach ($secondGroup as $product) {
// Imprimer la référence
$image2->text($product->href_product, $x, $y2, function($font) use ($fontSize) {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size(6);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

$imagePath = public_path(ltrim($product->image, '/'));

if (File::exists($imagePath)) {
    $shopimage2 = Image::make($imagePath)->resize(47, 46);
 $image2->insert($shopimage2, 'top-left', 108, $y2 + (-15)); 
}

$title = $product->designation;
if (!is_null($product->declinaison_libelle)) {
    $title .= " [" . $product->declinaison_libelle . "]"; // Appending the declinaison libelle in square brackets
}

$titleLines = wordwrap($title, 40, "\n", true);
$titleLinesArray = explode("\n", $titleLines);

foreach ($titleLinesArray as $i => $titleLine) {
    $image2->text($titleLine, $x + 126, $y2 - 4 + ($i * 12), function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
}


$fontSizeSmaller = $fontSize - 3;  
$addresseeLines = wordwrap($product->addressee, 40, "\n", true);
$addresseeLinesArray = explode("\n", $addresseeLines);

foreach ($addresseeLinesArray as $i => $addresseeLine) {
    $image2->text($addresseeLine, $x + 126, $y - 4 + 12+ (($i + count($titleLinesArray)) * 12), function($font) use ($fontSizeSmaller) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSizeSmaller);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
}


// Imprimer la quantité




$image2->text($product->quantity, $x + 355, $y2, function($font) use ($fontSize) {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size($fontSize);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

// Imprimer le prix unitaire
$ttc = number_format($product->ttc, 2, ',', ' ')  . " €";
$image2->text($ttc, $x + 428, $y2, function($font) use ($fontSize) {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size($fontSize);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

// Imprimer le prix total
$ttl = number_format($product->sub_total, 2, ',', ' ')  . " €";
$image2->text($ttl, $x + 533, $y2, function($font) use ($fontSize) {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size($fontSize);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

$y2 += 54;
}

$image2->text($bill->method_payment, 420, 829, function($font)  {
$font->file(public_path('fonts/arial.ttf'));
$font->size(16);
$font->color('#00000');
$font->align('left');
$font->valign('top');
});

$payment_total_amount = number_format($bill->payment_total_amount, 2, ',', ' ')  . " €";
$image2->text(	$payment_total_amount, 581, 755, function($font)  {
$font->file(public_path('fonts/arial.ttf'));
$font->size(12);
$font->color('#00000');
$font->align('left');
$font->valign('top');
});


$image2->text("En ".$bill->number." fois", 108, 755, function($font)  {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size(16);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});


$pdf1 = PDF::loadHTML('<img src="data:image/' . $image->mime . ';base64,' . base64_encode($image->encode()) . '">');
$pdf2 = PDF::loadHTML('<img src="data:image/' . $image2->mime . ';base64,' . base64_encode($image2->encode()) . '">');
$pdf3 = PDF::loadHTML('<img src="data:image/' . $image3->mime . ';base64,' . base64_encode($image3->encode()) . '">');

// Save the PDFs to temporary files
$tempFile1 = tempnam(sys_get_temp_dir(), 'pdf');
$tempFile3 = tempnam(sys_get_temp_dir(), 'pdf');
$tempFile2 = tempnam(sys_get_temp_dir(), 'pdf');
$pdf1->save($tempFile1);
$pdf3->save($tempFile3);
$pdf2->save($tempFile2);

// Merge the PDFs into a single PDF
$pdf = new Fpdi();
$pageCount1 = $pdf->setSourceFile($tempFile1);
for ($pageIndex = 1; $pageIndex <= $pageCount1; $pageIndex++) {
    $pdf->AddPage();
    $templateId = $pdf->importPage($pageIndex);
    $pdf->useTemplate($templateId);
}
$pageCount3 = $pdf->setSourceFile($tempFile3);
for ($pageIndex = 1; $pageIndex <= $pageCount3; $pageIndex++) {
    $pdf->AddPage();
    $templateId = $pdf->importPage($pageIndex);
    $pdf->useTemplate($templateId);
}
$pageCount2 = $pdf->setSourceFile($tempFile2);
for ($pageIndex = 1; $pageIndex <= $pageCount2; $pageIndex++) {
    $pdf->AddPage();
    $templateId = $pdf->importPage($pageIndex);
    $pdf->useTemplate($templateId);
}

// Send the merged PDF to the browser for download
$pdf->Output('Facture-'.$bill->id.'.pdf', 'D');


 

}
}

public function generatePDFfactureOutput($id)
{
    // Récupération des informations de facture
    $billsQuery = DB::table('bills')
            ->join('users', 'bills.user_id', '=', 'users.user_id')
            ->join('bills_status', 'bills.status', '=', 'bills_status.id')
            ->join('bills_payment_method', 'bills.payment_method', '=', 'bills_payment_method.id')
            ->where('bills.id', $id)
            ->select('bills.*','bills_payment_method.payment_method as method_payment', 'bills_status.row_color', 'bills_status.status as bill_status','users.name', 'users.lastname', 'users.email', 'users.phone', 'users.address', 'users.city', 'users.zip', 'users.country','users.birthdate');
        $oldBillsQuery = DB::table('old_bills')
            ->join('users', 'old_bills.user_id', '=', 'users.user_id')
            ->join('bills_status', 'old_bills.status', '=', 'bills_status.id')
            ->join('bills_payment_method', 'old_bills.payment_method', '=', 'bills_payment_method.id')
            ->where('old_bills.id', $id)
            ->select('old_bills.*','bills_payment_method.payment_method as method_payment', 'bills_status.row_color', 'bills_status.status as bill_status','users.name', 'users.lastname', 'users.email', 'users.phone', 'users.address', 'users.city', 'users.zip', 'users.country','users.birthdate');

        $bill = $billsQuery->union($oldBillsQuery)->first();

    // Récupération des informations de produits
    $shop = DB::table('liaison_shop_articles_bills')
        ->select('quantity', 'ttc', 'sub_total', 'designation', 'addressee', 'shop_article.image', 'liaison_shop_articles_bills.id_liaison','liaison_shop_articles_bills.href_product')
        ->join('bills', 'bills.id', '=', 'liaison_shop_articles_bills.bill_id')
        ->join('shop_article', 'shop_article.id_shop_article', '=', 'liaison_shop_articles_bills.id_shop_article')
        ->where('bills.id', '=', $id)
        ->get();

        if ($shop->count() <= 7) {

    
    // Chargement de l'image de fond
    $image = Image::make(public_path('assets/images/Facture_page.png'));
    $image->resize(700, 1000); // Replace 800 and 1200 with the desired width and height

    $now = now();
    $image->text(\Carbon\Carbon::parse($bill->date_bill)->format('d-m-Y'), 449, 234, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(14);
        $font->color('#000000');
        $font->align('left');
        $font->valign('top');
    });

    // Ajout de la référence de la facture sur l'image
    $image->text($bill->ref, 449, 258, function($font) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(18);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $image->text($bill->name.' '.$bill->lastname, 369, 139, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(12);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
    
    $address = $bill->address;
    $image->text($address, 369, 150, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(12);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $image->text($bill->zip, 409, 179, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(12);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $image->text($bill->city, 378, 193, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(12);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $image->text($bill->method_payment, 420, 829, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(16);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $payment_total_amount = number_format($bill->payment_total_amount, 2, ',', ' ')  . " €";
    $image->text(	$payment_total_amount, 581, 755, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(12);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    // Coordonnées de la première ligne de produit
    $x = 35;
    $y = 334;
    $fontSize = 10;
    // Boucle sur les produits
    foreach ($shop as $product) {

    // Imprimer la référence
    $image->text($product->href_product, $x, $y, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(6);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
    $imagePath = public_path(ltrim($product->image, '/'));
    
    if (File::exists($imagePath)) {
        $shopImage = Image::make($imagePath)->resize(47, 46);
    $image->insert($shopImage, 'top-left', 108, $y + (-15));
    }
    
    $title = $product->designation;
if (!is_null($product->declinaison_libelle)) {
    $title .= " [" . $product->declinaison_libelle . "]"; // Appending the declinaison libelle in square brackets
}

$titleLines = wordwrap($title, 40, "\n", true);
$titleLinesArray = explode("\n", $titleLines);

foreach ($titleLinesArray as $i => $titleLine) {
    $image->text($titleLine, $x + 126, $y - 4 + ($i * 12), function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
}

    $fontSizeSmaller = $fontSize - 3;  
    $addresseeLines = wordwrap($product->addressee, 40, "\n", true);
    $addresseeLinesArray = explode("\n", $addresseeLines);
    
    foreach ($addresseeLinesArray as $i => $addresseeLine) {
        $image->text($addresseeLine, $x + 126, $y + (($i + count($titleLinesArray)) * 12), function($font) use ($fontSizeSmaller) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size($fontSizeSmaller);
            $font->color('#00000');
            $font->align('left');
            $font->valign('top');
        });
    }
    

    // Imprimer la quantité
    



    $image->text($product->quantity, $x + 355, $y, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    // Imprimer le prix unitaire
    $ttc = number_format($product->ttc, 2, ',', ' ')  . " €";
    $image->text($ttc, $x + 428, $y, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    // Imprimer le prix total
    $ttl = number_format($product->sub_total, 2, ',', ' ')  . " €";
    $image->text($ttl, $x + 533, $y, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    // Ajouter l'espacement entre les produits
    $y += 54;
}
    

    // Conversion de l'image modifiée en PDF
    $pdf = PDF::loadHTML('<img src="data:image/' . $image->mime . ';base64,' . base64_encode($image->encode()) . '">');
    return $pdf->download('Facture-'.$bill->id.'.pdf');}


    elseif ($shop->count() > 7 && $shop->count() < 23) {
        $shop = $shop->toArray();
        $firstGroup = array_slice($shop, 0, 11);
        $secondGroup = array_slice($shop, 11);
        $image = Image::make(public_path('assets/images/FactureMulti_Page1.png'));
        $image->resize(700, 1000);
        $image2 = Image::make(public_path('assets/images/FactureMulti_Page3.png'));
        $image2->resize(700, 1000);

        
    $now = now();
    $image->text(\Carbon\Carbon::parse($bill->date_bill)->format('d-m-Y'), 449, 234, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(14);
        $font->color('#000000');
        $font->align('left');
        $font->valign('top');
    });

    // Ajout de la référence de la facture sur l'image
    $image->text($bill->ref, 449, 258, function($font) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(18);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $image->text($bill->name.' '.$bill->lastname, 369, 139, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(12);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
    
    $address = $bill->address;
    $image->text($address, 369, 150, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(12);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $image->text($bill->zip, 409, 179, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(12);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $image->text($bill->city, 378, 193, function($font)  {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(12);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $x = 35;
    $y = 334;
    $fontSize = 10;
    // Boucle sur les produits
    foreach ($firstGroup as $product) {

    // Imprimer la référence
    $image->text($product->href_product, $x, $y, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(6);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $imagePath = public_path(ltrim($product->image, '/'));
    if (File::exists($imagePath)) {
        $shopimage = Image::make($imagePath)->resize(47, 46);
    $image->insert($shopimage, 'top-left', 108, $y + (-15));
    }
    
    
    $title = $product->designation;

if (isset($product->declinaison_libelle) && !is_null($product->declinaison_libelle)) {
    $title .= " [" . $product->declinaison_libelle . "]"; 
}

$titleLines = wordwrap($title, 40, "\n", true);
$titleLinesArray = explode("\n", $titleLines);

foreach ($titleLinesArray as $i => $titleLine) {
    $image->text($titleLine, $x + 126, $y - 4 + ($i * 12), function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
}


    $fontSizeSmaller = $fontSize - 3;  
    $addresseeLines = wordwrap($product->addressee, 40, "\n", true);
    $addresseeLinesArray = explode("\n", $addresseeLines);
    
    foreach ($addresseeLinesArray as $i => $addresseeLine) {
        $image->text($addresseeLine, $x + 126, $y + (($i + count($titleLinesArray)) * 12), function($font) use ($fontSizeSmaller) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size($fontSizeSmaller);
            $font->color('#00000');
            $font->align('left');
            $font->valign('top');
        });
    }
    
    // Imprimer la quantité
    



    $image->text($product->quantity, $x + 355, $y, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    // Imprimer le prix unitaire
    $ttc = number_format($product->ttc, 2, ',', ' ')  . " €";
    $image->text($ttc, $x + 428, $y, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    // Imprimer le prix total
    $ttl = number_format($product->sub_total, 2, ',', ' ')  . " €";
    $image->text($ttl, $x + 533, $y, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    // Ajouter l'espacement entre les produits
    $y += 54;
}

$y2 = 111;

foreach ($secondGroup as $product) {
    
    // Imprimer la référence
    $image2->text($product->href_product, $x, $y2, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(6);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $imagePath = public_path(ltrim($product->image, '/'));
    if (File::exists($imagePath)) {
        $shopimage2 = Image::make($imagePath)->resize(47, 46);
    $image2->insert($shopimage2, 'top-left', 108, $y2 + (-15));
    }
    
    $title = $product->designation;

if (isset($product->declinaison_libelle) && !is_null($product->declinaison_libelle)) {
    $title .= " [" . $product->declinaison_libelle . "]"; 
}

$titleLines = wordwrap($title, 40, "\n", true);
$titleLinesArray = explode("\n", $titleLines);

foreach ($titleLinesArray as $i => $titleLine) {
    $image2->text($titleLine, $x + 126, $y2 - 4 + ($i * 12), function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
}
    
    $fontSizeSmaller = $fontSize - 3;  
    $addresseeLines = wordwrap($product->addressee, 40, "\n", true);
    $addresseeLinesArray = explode("\n", $addresseeLines);
    
    foreach ($addresseeLinesArray as $i => $addresseeLine) {
        $image2->text($addresseeLine, $x + 126, $y - 4 + 12+ (($i + count($titleLinesArray)) * 12), function($font) use ($fontSizeSmaller) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size($fontSizeSmaller);
            $font->color('#00000');
            $font->align('left');
            $font->valign('top');
        });
    }
    

    // Imprimer la quantité
    



    $image2->text($product->quantity, $x + 355, $y2, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    // Imprimer le prix unitaire
    $ttc = number_format($product->ttc, 2, ',', ' ')  . " €";
    $image2->text($ttc, $x + 428, $y2, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    // Imprimer le prix total
    $ttl = number_format($product->sub_total, 2, ',', ' ')  . " €";
    $image2->text($ttl, $x + 533, $y2, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });

    $y2 += 54;
}

$image2->text($bill->method_payment, 420, 829, function($font)  {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size(16);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

$payment_total_amount = number_format($bill->payment_total_amount, 2, ',', ' ')  . " €";
$image2->text(	$payment_total_amount, 581, 755, function($font)  {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size(12);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});




// Convert the images to PDFs
$pdf1 = PDF::loadHTML('<img src="data:image/' . $image->mime . ';base64,' . base64_encode($image->encode()) . '">');
$pdf2 = PDF::loadHTML('<img src="data:image/' . $image2->mime . ';base64,' . base64_encode($image2->encode()) . '">');

// Save the PDFs to temporary files
$tempFile1 = tempnam(sys_get_temp_dir(), 'pdf');
$tempFile2 = tempnam(sys_get_temp_dir(), 'pdf');
$pdf1->save($tempFile1);
$pdf2->save($tempFile2);

// Merge the PDFs into a single PDF
$pdf = new Fpdi();
$pageCount1 = $pdf->setSourceFile($tempFile1);
for ($pageIndex = 1; $pageIndex <= $pageCount1; $pageIndex++) {
    $pdf->AddPage();
    $templateId = $pdf->importPage($pageIndex);
    $pdf->useTemplate($templateId);
}
$pageCount2 = $pdf->setSourceFile($tempFile2);
for ($pageIndex = 1; $pageIndex <= $pageCount2; $pageIndex++) {
    $pdf->AddPage();
    $templateId = $pdf->importPage($pageIndex);
    $pdf->useTemplate($templateId);
}

// Send the merged PDF to the browser for download
$pdf->Output('Facture-'.$bill->id.'.pdf', 'D');



}else{$shop = $shop->toArray();
    $firstGroup = array_slice($shop, 0, 11);
    $thirdGroup = array_slice($shop, 11, 15);
    $secondGroup = array_slice($shop, 26);

    $image = Image::make(public_path('assets/images/FactureMulti_Page1.png'));
    $image->resize(700, 1000);
    $image2 = Image::make(public_path('assets/images/FactureMulti_Page3.png'));
    $image2->resize(700, 1000);
    $image3 = Image::make(public_path('assets/images/FactureMulti_Page2.png'));
    $image3->resize(700, 1000);

    
$now = now();
$image->text(\Carbon\Carbon::parse($bill->date_bill)->format('d-m-Y'), 449, 234, function($font)  {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size(14);
    $font->color('#000000');
    $font->align('left');
    $font->valign('top');
});

// Ajout de la référence de la facture sur l'image
$image->text($bill->ref, 449, 258, function($font) {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size(18);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

$image->text($bill->name.' '.$bill->lastname, 369, 139, function($font)  {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size(12);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

$address = $bill->address;
$image->text($address, 369, 150, function($font)  {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size(12);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

$image->text($bill->zip, 409, 179, function($font)  {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size(12);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

$image->text($bill->city, 378, 193, function($font)  {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size(12);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

$x = 35;
$y = 334;
$fontSize = 10;
// Boucle sur les produits
foreach ($firstGroup as $product) {

// Imprimer la référence
$image->text($product->href_product, $x, $y, function($font) use ($fontSize) {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size(6);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

$imagePath = public_path(ltrim($product->image, '/'));
if (File::exists($imagePath)) {
    $shopimage = Image::make($imagePath)->resize(47, 46);
$image->insert($shopimage, 'top-left', 108, $y + (-15));
}

$title = $product->designation;

if (isset($product->declinaison_libelle) && !is_null($product->declinaison_libelle)) {
    $title .= " [" . $product->declinaison_libelle . "]"; 
}

$titleLines = wordwrap($title, 40, "\n", true);
$titleLinesArray = explode("\n", $titleLines);

foreach ($titleLinesArray as $i => $titleLine) {
    $image->text($titleLine, $x + 126, $y - 4 + ($i * 12), function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
}

$fontSizeSmaller = $fontSize - 3;  
$addresseeLines = wordwrap($product->addressee, 40, "\n", true);
$addresseeLinesArray = explode("\n", $addresseeLines);

foreach ($addresseeLinesArray as $i => $addresseeLine) {
    $image->text($addresseeLine, $x + 126, $y + (($i + count($titleLinesArray)) * 12), function($font) use ($fontSizeSmaller) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSizeSmaller);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
}


// Imprimer la quantité




$image->text($product->quantity, $x + 355, $y, function($font) use ($fontSize) {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size($fontSize);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

// Imprimer le prix unitaire
$ttc = number_format($product->ttc, 2, ',', ' ')  . " €";
$image->text($ttc, $x + 428, $y, function($font) use ($fontSize) {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size($fontSize);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

// Imprimer le prix total
$ttl = number_format($product->sub_total, 2, ',', ' ')  . " €";
$image->text($ttl, $x + 533, $y, function($font) use ($fontSize) {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size($fontSize);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

// Ajouter l'espacement entre les produits
$y += 54;
}

$y2 = 111;
foreach ($thirdGroup as $product) {

    // Imprimer la référence
    $image3->text($product->href_product, $x, $y2, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size(6);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
    
    $imagePath = public_path(ltrim($product->image, '/'));
    if (File::exists($imagePath)) {
        $shopimage3 = Image::make($imagePath)->resize(47, 46);
    $image3->insert($shopimage3, 'top-left', 108, $y2 + (-15));
    }
    
    $title = $product->designation;

if (isset($product->declinaison_libelle) && !is_null($product->declinaison_libelle)) {
    $title .= " [" . $product->declinaison_libelle . "]"; 
}

$titleLines = wordwrap($title, 40, "\n", true);
$titleLinesArray = explode("\n", $titleLines);

foreach ($titleLinesArray as $i => $titleLine) {
    $image3->text($titleLine, $x + 126, $y2 - 4 + ($i * 12), function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
}


    $fontSizeSmaller = $fontSize - 3;  
    $addresseeLines = wordwrap($product->addressee, 40, "\n", true);
    $addresseeLinesArray = explode("\n", $addresseeLines);
    
    foreach ($addresseeLinesArray as $i => $addresseeLine) {
        $image3->text($addresseeLine, $x + 126, $y - 4 + 12+ (($i + count($titleLinesArray)) * 12), function($font) use ($fontSizeSmaller) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size($fontSizeSmaller);
            $font->color('#00000');
            $font->align('left');
            $font->valign('top');
        });
    }
    
    
    // Imprimer la quantité
    
    
    
    
    $image3->text($product->quantity, $x + 355, $y2, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
    
    // Imprimer le prix unitaire
    $ttc = number_format($product->ttc, 2, ',', ' ')  . " €";
    $image3->text($ttc, $x + 428, $y2, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
    
    // Imprimer le prix total
    $ttl = number_format($product->sub_total, 2, ',', ' ')  . " €";
    $image3->text($ttl, $x + 533, $y2, function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
    
    $y2 += 54;
    }
    
    
foreach ($secondGroup as $product) {

// Imprimer la référence
$image2->text($product->href_product, $x, $y2, function($font) use ($fontSize) {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size(6);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

$imagePath = public_path(ltrim($product->image, '/'));
if (File::exists($imagePath)) {
    $shopimage2 = Image::make($imagePath)->resize(47, 46);
$image2->insert($shopimage2, 'top-left', 108, $y2 + (-15));
}

$title = $product->designation;

if (isset($product->declinaison_libelle) && !is_null($product->declinaison_libelle)) {
    $title .= " [" . $product->declinaison_libelle . "]"; 
}

$titleLines = wordwrap($title, 40, "\n", true);
$titleLinesArray = explode("\n", $titleLines);

foreach ($titleLinesArray as $i => $titleLine) {
    $image2->text($titleLine, $x + 126, $y2 - 4 + ($i * 12), function($font) use ($fontSize) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSize);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
}


$fontSizeSmaller = $fontSize - 3;  
$addresseeLines = wordwrap($product->addressee, 40, "\n", true);
$addresseeLinesArray = explode("\n", $addresseeLines);

foreach ($addresseeLinesArray as $i => $addresseeLine) {
    $image2->text($addresseeLine, $x + 126, $y - 4 + 12+ (($i + count($titleLinesArray)) * 12), function($font) use ($fontSizeSmaller) {
        $font->file(public_path('fonts/arial.ttf'));
        $font->size($fontSizeSmaller);
        $font->color('#00000');
        $font->align('left');
        $font->valign('top');
    });
}


// Imprimer la quantité




$image2->text($product->quantity, $x + 355, $y2, function($font) use ($fontSize) {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size($fontSize);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

// Imprimer le prix unitaire
$ttc = number_format($product->ttc, 2, ',', ' ')  . " €";
$image2->text($ttc, $x + 428, $y2, function($font) use ($fontSize) {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size($fontSize);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

// Imprimer le prix total
$ttl = number_format($product->sub_total, 2, ',', ' ')  . " €";
$image2->text($ttl, $x + 533, $y2, function($font) use ($fontSize) {
    $font->file(public_path('fonts/arial.ttf'));
    $font->size($fontSize);
    $font->color('#00000');
    $font->align('left');
    $font->valign('top');
});

$y2 += 54;
}

$image2->text($bill->method_payment, 420, 829, function($font)  {
$font->file(public_path('fonts/arial.ttf'));
$font->size(16);
$font->color('#00000');
$font->align('left');
$font->valign('top');
});

$payment_total_amount = number_format($bill->payment_total_amount, 2, ',', ' ')  . " €";
$image2->text(	$payment_total_amount, 581, 755, function($font)  {
$font->file(public_path('fonts/arial.ttf'));
$font->size(12);
$font->color('#00000');
$font->align('left');
$font->valign('top');
});




$pdf1 = PDF::loadHTML('<img src="data:image/' . $image->mime . ';base64,' . base64_encode($image->encode()) . '">');
$pdf2 = PDF::loadHTML('<img src="data:image/' . $image2->mime . ';base64,' . base64_encode($image2->encode()) . '">');
$pdf3 = PDF::loadHTML('<img src="data:image/' . $image3->mime . ';base64,' . base64_encode($image3->encode()) . '">');

// Save the PDFs to temporary files
$tempFile1 = tempnam(sys_get_temp_dir(), 'pdf');
$tempFile3 = tempnam(sys_get_temp_dir(), 'pdf');
$tempFile2 = tempnam(sys_get_temp_dir(), 'pdf');
$pdf1->save($tempFile1);
$pdf3->save($tempFile3);
$pdf2->save($tempFile2);

// Merge the PDFs into a single PDF
$pdf = new Fpdi();
$pageCount1 = $pdf->setSourceFile($tempFile1);
for ($pageIndex = 1; $pageIndex <= $pageCount1; $pageIndex++) {
    $pdf->AddPage();
    $templateId = $pdf->importPage($pageIndex);
    $pdf->useTemplate($templateId);
}
$pageCount3 = $pdf->setSourceFile($tempFile3);
for ($pageIndex = 1; $pageIndex <= $pageCount3; $pageIndex++) {
    $pdf->AddPage();
    $templateId = $pdf->importPage($pageIndex);
    $pdf->useTemplate($templateId);
}
$pageCount2 = $pdf->setSourceFile($tempFile2);
for ($pageIndex = 1; $pageIndex <= $pageCount2; $pageIndex++) {
    $pdf->AddPage();
    $templateId = $pdf->importPage($pageIndex);
    $pdf->useTemplate($templateId);
}

// Send the merged PDF to the browser for download
$pdf->Output('Facture-'.$bill->id.'.pdf', 'D');


 

}
}
}