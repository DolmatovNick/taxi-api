# Taxi API

## Задание
 
Создать API для сайта “службы такси”: нужно хранить данные о
1. водителе (фио, должность)
2. операторе (фио, должность)
3. автомобиле (марка, цвет, номер) - на одном автомобиле могут работать несколько
4. водителей, у одного водителя может быть несколько автомобилей,
5. заказы - данные о том, когда и какой водитель какой заказ выполнял и на каком
автомобиле (время заказа, адрес отправления, адрес назначения, пришёл ли клиент,
оператор)

Для отправки запросов использовать RestAPI.

1. Запрос к API инициализации /init - скрипт должен удалять все таблицы из БД, если
такие имеются и создавать все необходимые таблицы, после чего внести в них
тестовые данные: не менее 5 водителей, не менее 10 автомобилей, не менее 30
записей в истории заказов, не менее 3х заказов “в процессе”.

`GET /api/v1/init`

2. Запросы на добавление/изменения/удаления данных - не реализовывать их, создав
методы, которые будут выводить “Not Implemented”.

`GET /api/v1/notImplementedPoint`

Запросы на получение данных (в скобках - ожидаемый на тестовых данных ответ):
1. Найти всех водителей, которые не имеют ни одного заказа за всё время
работы (минимум 2 в ответе)

`GET /api/v1/drivers?notHave[]=orders`

2. Найти все заказы, которые не имеют успешного статуса и имена операторов,
которые их приняли (минимум 5 записей в ответе, операторы могут
повторяться)

`GET /api/v1/orders?dontHaveStatuses[]=6`

3. Найти всех водителей, которые имеют более 100 выполненных заказов (“Not
Found”)

`GET /api/v1/drivers?haveOrdersCount={"min":100}&haveStatuses[]=6`

4. Найти всех водителей, которые имеют более 10 выполненных заказов
(минимум 1 водитель)

`GET /api/v1/drivers?haveOrdersCount={"min":10}&haveStatuses[]=6`

5. Вывести список водителей в порядке убывания количества выполненных
заказов (имена всех водителей)

`GET /api/v1/drivers?orderByOrdersCount=DESC`

6. Найти список автомобилей, на которых работают более 1го водителя и
менее 4х водителей (минимум 2 варианта)

`GET /api/v1/cars?haveDriversCount={"min":1,"max":4}`

7. Список всех сотрудников службы такси (водителей и операторов)
8. Предусмотреть запрос для авторизации перед получением доступа к
остальным частям API. Логин: admin, пароль: 123

```
("Успешный статус заказа", то же самое что "выполненный заказ" - клиент доставлен по
адресу назначения)
```

Все данные должны возвращаться в формате JSON.
Использовать php 5.6+, любой php фреймворк, либо без него, MySQL. В пояснительной
записке описать работу API с указанием какие использовались паттерны проектирования
кода и почему.

# Common API endpoints

| № | Status | Order complete |  
| :---: | :--- | :---: |  
| 1 | Заказ поступил   | NO  | 
| 2 | Водитель выехал   | NO  | 
| 3 | Водитель на месте  | NO  | 
| 4 | Клиент в машине   | NO  | 
| 5 | Клиент доставлен   | NO  | 
| 6 | Заказ выполнен   | YES  | 
| 7 | Заказ не выполнен и закрыт | YES  | 

# API endpoints

Support endpoints

| № | Name | Method | Need auth| Endpoint|  
| :---: | :--- | :--- | :---: | :--- | 
| 1 | Init app   | GET  | Yes | /api/v1/init |
| 2 | Get drivers | GET | Yes | /api/v1/drivers |
| 3 | Get employees | GET  | Yes | /api/v1/employees|
| 4 | Get cars   | GET  | Yes | /api/v1/cars |

## Drivers filters

| № | Name | Endpoint | Filter|  
| :---: | :--- | :--- | :--- | 
| 1 | Get drivers having orders   | /drivers  | &have[]=orders |
| 2 | Get drivers having no orders   | /drivers  |  &notHave=orders |

## Orders filters

| № | Name | Endpoint | Filter|  
| :---: | :--- | :--- | :--- | 
| 1 | Get orders don't have statuses  | /drivers  | &dontHaveStatuses[]=6 |
| 2 | Get drivers having no orders   | /drivers  |  &haveOrder=0 |

## Cars filters

| № | Name | Endpoint | Filter| Note|  
| :---: | :--- | :--- | :--- | :--- | 
| 1 | Get cars which have from `min` till `max` drivers in orders | /cars  | &haveDriversCount={"min":1,"max":100} | You can use only `min` or only `max` format example: `{"min":1}` and `{"max":100}` | 


## Get drivers
##### Найти всех водителей, которые не имеют ни одного заказа за всё время работы (минимум 2 в ответе)
##### GET /api/v1/drivers?haveOrders=0
##### Response
```json
{  
   data:[  
      {  
         id:1,
         fio:"Mr. Desmond Eichmann Sr."
      },
      {  
         id:2,
         fio:"Dr. Daryl Flatley"
      }
   ],
   links:{  
    
   },
   meta:{  
    
   }
}
```
##### Success code is 200

## Get orders
##### GET /api/v1/orders?&dontHaveStatuses[]=6
```
##### Response
```json
{  
   data:[  
      {  
         id:6,
         operator:"Cecile Swaniawski",
         points:[  
            {  
               id:13,
               address:"982 Medhurst Manors Suite 394 Robelview, PA 54314-5251",
               type:"pickup"
            },
            {  
               id:14,
               address:"80746 Merl Pike Eldoraberg, PA 03493",
               type:"stepout"
            }
         ]
      },
      {  
         id:9,
         operator:"Miss Rosetta Jerde",
         points:[  
            {  
               id:19,
               address:"1233 Moore Pass Mitchellburgh, IA 38561-4032",
               type:"pickup"
            },
            {  
               id:20,
               address:"640 Altenwerth Ville Suite 734 Lake Luciousville, AK 69536",
               type:"stepout"
            }
         ]
      }
   ],
   links:{  
    
   },
   meta:{  
    
   }
}
```

## Edit recipe
##### PUT /recipes/{recipe}
```json
{
    "name"  : "Sugar and water recipe",
    "text"	: "Take one spoon of sugar. And one glass of water. Mix. Enjoy!"
}
```
##### Response
```json
{
    "id"            : 1,
    "recipe_url"	: "/recipes/1",
    "upload_image_method"	: "PUT",
    "upload_image_url"	    : "/recipes/1/image"
}
```

## Add image to recipe
##### PUT /recipes/{recipe}/iamge

How to add image: Use HTTP HEAD binary format, json is no needs  
```json
{
    "id": 1,
    "recipe_url": "/recipes/1",
    "image_url": "/images/1.png"
}
```
##### Response
```json
{
    "id": 1,
    "recipe_url": "/recipes/1",
    "image_url": "/images/1.png"
}
```
     Note: API changes format input images to .png

## Delete recipe
### DELETE /recipes/{recipe}
##### Success code is 204

## Get user recipe list
##### GET /users/{user_id}recipes

##### Response
```json
[
    {
        "id"  : 1,
        "name"  : "Sugar and water recipe",
        "text"	: "Take one spoon of sugar. And one glass of water. Mix. Enjoy!",
        "image_url" : "/recipes/1.png"
    },
    {
        "id"  : 2,
        "name"  : "Sugar and water recipe",
        "text"	: "Take one spoon of sugar. And one glass of water. Mix. Enjoy!",
        "image_url" : "/recipes/2.png"
    }
]
```
##### Success code is 200

# How to use endpoints

## 1 Setup

1. Login using the credentials login: `admin@mail.com` and password: `123`
1. Run `api/v1/init` API endpoint for set up database

## 2 Authenticate

You have to add "Authorization" field in HTTP HEAD and fill it with a "key" 
from the "Create user" endpoint