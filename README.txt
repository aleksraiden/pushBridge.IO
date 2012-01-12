
$push = new pushBridge_IO( new pushBridge_Adapter_Pusher(Array('app_id' => '1111', 'password' => 'qwerty')) );

API:
	$push->getConnection() - получает сам обьект соединения (родной API транспорта)
	$push->send() - отправляет данные
	$push->close() - закрывает соединение

Adapters

AdapterInterface - абстрактый интерфейс 

Адаптеры:
	- pushBridge_Adapter_Pusher - для сервиса pusher.com
	- pushBridge_Adapter_Pubnub - для pubnub
	- pushBridge_Adapter_Beaconpush
	- pushBridge_Adapter_Partcl
	- pushBridge_Adapter_ioBridge
	- pushBridge_Adapter_xStreamly - ( http://x-stream.ly/ )



Examples

Partcl.com:

	$push = new pushBridge_IO( new pushBridge_Adapter_Partcl(Array('secretKey' => '<Your secret key>')) );
	$push->send('Hello world from pushBridge.IO', '<Your tag id>', Array('serialize' => false));

Pusher.com: 

	$push = new pushBridge_IO( new pushBridge_Adapter_Pusher(Array('appId' => '<Your app Id>', 'authKey' => '<Your key>', 'secretKey' => '<Your secret key>', 'debug' => true)) );
	$push->send('Hello world from pushBridge.IO', 'test_channel', Array('serialize' => false, 'event' => 'push_test'));
	
