
Example

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


