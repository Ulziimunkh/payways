## Суурьлуулалт

composer.json файлд доорх байдлаар энэ git агуулхыг бүртгэнэ.

```
"repositories": [
{
    "type": "git",
    "url":  "git@github.com:selmonal/payways.git"
}
```

Тэгээд require хэсэгт доорх байдлаар дуудна.

```
"selmonal/payways": "dev-master"
```

Доорх ServiceProvider болон Facade ийг app.php тохиргооний файлд бүртгэнэ.

```
'providers' => [
	Selmonal\Payways\PaywaysServiceProvider::class,
],

'aliases' => [
	'Payways'   => 'Selmonal\Payways\PaywaysFacade'
]
```

Дараа нь өгөгдлийн санд transactions хүснэгт үүсгэх migration болон тохиргоо хийх payways.php файлыг төсөлд оруулна.

```
php artisan vendor:publish --provider="Selmonal\Payways\PaywaysServiceProvider"
php artisan migrate
```

## Хаан банк

Банкнаас өгсөн хэрэглэгчийн нэр болон нууц үгийг payways.php тохиргооний файлд тохируулна. Энд returnUrl гэдэг нь банкны терминал дээр гүйлгээ хийгдээд буцаан дуудах url хаяг байх юм. 

Гүйлгээ хийхдээ дараах байдлаар хийнэ.

```
$driverName = 'khan';

$response = Payways::with($driverName) // Гүйлгээ хийх driver: khan, golomt, log
	->transaction() // Шинэ гүйлгээ үүсгэж байна.
	->payable($order) // Юуны төлбөр төлөлт энэ гүйлгээгээр хийх гэж байгаагаа тохируулна
	->create() // Гүйлгээг өгөгдлийн санд хадгалж байна.
	->proccess(); // Үүссэн энэ гүйлгээг хийх үйл ажиллагааг эхлүүлнэ.

// Энэ нөхцөл биелэж байгаа бол банктай харьцан гүйлгээ хийхэд бэлэн 
// болсон байна гэсэн үг.
if ($response->isRedirect()) {
	$response->redirect() // банкны хуудас уруу үсрэнэ.
} else {
	// Гүйлгээ хийхэд алдаа гарсан гэсэн үг.
	echo $response->getMessage();
}
```

Банкны терминал дээр гүйлгээ хийгдээд буцаж ирсэн хариуг боловсруулах.

```
Route::get('payways/khan', function (Request $request) {
	$transaction = Transaction::findByReference($request->get('orderId'), 'khan');
	
	// Гүйлгээний лавлагаа хийхээр банкны сервертэй холбогдоно.
    $response = $transaction->completeProcess();

    if ($response->isSuccessful()) {
    	// Энд гүйлгээ амжилттай хийгдсэн болохыг илтгэж байна.
    	// Тиймээс дараах зүйлийг хийж болох юм
    	$transaction->payable->status = 'paid';
    	$transaction->payable->save();

    	event(new OrderWasPaid($transaction->payable));
    } else {
    	// Гүйлгээний лавлагаагаар гүйлгээ амжилтгүй болсон гэж дүгнэгдсэн байна.
    	echo $response->getMessage();
    }
});
```