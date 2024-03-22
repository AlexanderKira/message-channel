# Установка проекта
Копирование репозитория

    git clone https://github.com/AlexanderKira/message-channel.git
Зайти в проект

    cd message-channel
Скопировать env.example в .env

    cp .env.example .env
Установить composer

    composer install
Зайти в env и установить значения
Конфигурация бд

    DB_CONNECTION=mysql
    DB_HOST=mysql
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=sail
    DB_PASSWORD=password

Зайти в контейнер
    
    docker-compose up -d
    
    php artisan migrate

    npm run dev

# Работа в программе
Если установка прошла успешно,

Зарегестрировать пользователя 

    http://localhost/register

Я работаю через https://insomnia.rest/

Получить XSRF-TOKEN get

    http://localhost/sanctum/csrf-cookie

в каждом запросе нужно прописывать заголовки
    
    Accept => application/json
    X-XSRF-TOKEN => токен который мы получили ранее
    Referer => localhost 

отправить сообщение с типом all или authorized

    http://localhost/api/messages/store

    content => 'текст сообщения'
    type => 'all'

отправить сообщение private 

    http://localhost/api/messages/store

    content => 'привет тебе'
    type => 'private'
    recipient_id => '2'

ответ на сообщение 

    http://localhost/api/messages/reply

    content => 'текст ответа на сообщение 1'
    message_id => '1'

обновить сообщение по id POST

    http://localhost/api/messages/update/1
    
    content => 'текст для обновления сообщения 1'

получить все сообщения GET,
ответы и private сообщения которые отправлены вашему пользователю будут видны в списке

    http://localhost/api/messages/

по query параметрам можно фильтровать сообщения по типу и дате,
ответы и private сообщения которые отправлены вашему пользователю будут видны в списке

    type => 'private'
    start_date => '2024-03-18 10:02'
    end_date => '2024-03-20 10:20'

образец ответа

    {
        "data": {
            "id": 16,
            "content": "привет всем",
            "type": "private",
            "sender": {
                "id": 1,
                "name": "admin",
                "email": "admin@admin.com",
                "email_verified_at": null,
                "created_at": "2024-03-16T11:44:55.000000Z",
                "updated_at": "2024-03-16T11:44:55.000000Z"
            },
            "recipient": {
                "id": 2,
                "name": "user1",
                "email": "user1@user1.com",
                "email_verified_at": null,
                "created_at": "2024-03-18T02:57:51.000000Z",
                "updated_at": "2024-03-18T02:57:51.000000Z"
            },
            "replies": [
                {
                    "id": 6,
                    "content": "ну и тебе привет что-ли",
                    "user_id": 1,
                    "message_id": 16,
                    "created_at": "2024-03-22T02:03:29.000000Z",
                    "updated_at": "2024-03-22T02:03:29.000000Z"
                }
            ],
            "created_at": "2024-03-22T02:03:11.000000Z",
            "updated_at": "2024-03-22T02:03:11.000000Z"
        }
    }

получить сообщение по id GET

    http://localhost/api/messages/show/1


удалить сообщение POST

    http://localhost/api/messages/delete/1
    


    








  

