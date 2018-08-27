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
`POST /api/v1/notImplementedPoint`

Запросы на получение данных (в скобках - ожидаемый на тестовых данных ответ):
1. Найти всех водителей, которые не имеют ни одного заказа за всё время
работы (минимум 2 в ответе)

`GET /api/v1/drivers?notHave[]=orders`

2. Найти все заказы, которые не имеют успешного статуса и имена операторов,
которые их приняли (минимум 5 записей в ответе, операторы могут
повторяться)

`GET /api/v1/orders?notInStatus=6`

3. Найти всех водителей, которые имеют более 100 выполненных заказов (“Not
Found”)

`GET /api/v1/drivers?haveOrdersCount={"min":100}&orderStatus=6`

4. Найти всех водителей, которые имеют более 10 выполненных заказов
(минимум 1 водитель)

`GET /api/v1/drivers?haveOrdersCount={"min":10}&orderStatus=6`

5. Вывести список водителей в порядке убывания количества выполненных
заказов (имена всех водителей)

`GET /api/v1/drivers?orderByOrders=DESC`

6. Найти список автомобилей, на которых работают более 1го водителя и
менее 4х водителей (минимум 2 варианта)

`GET /api/v1/cars?haveDriversCount={"min":1,"max":4}`

7. Список всех сотрудников службы такси (водителей и операторов)

`GET /api/v1/employees`

8. Предусмотреть запрос для авторизации перед получением доступа к
остальным частям API. Логин: admin, пароль: 123

`POST /api/v1/login` - для полуения токена

```
("Успешный статус заказа", то же самое что "выполненный заказ" - клиент доставлен по
адресу назначения)
```

Все данные должны возвращаться в формате JSON.
Использовать php 5.6+, любой php фреймворк, либо без него, MySQL. В пояснительной
записке описать работу API с указанием какие использовались паттерны проектирования
кода и почему.

# Пояснительная записка

Было реализованно Api согласно заданию. Были добавлены endpoints для получения 
списков водителей, заказов и сотрудников в целом через GET метод.    
Для каждого endpoint были добавлены фильтры и сортировки, позволяющие получать 
выборки необходимые в задании, фильтры можно комбинировать, получая гибкую возможноть 
выборки.

В базе данных операторы и водители хранятся в разных таблицах `operators`, `drivers`. 
Такая реализация, в отличии от общей таблицы `employees`, позволяет 
не предпринимать дополнительных действий для поддержания консистентности БД 
(Например, сейчас имея отдельные foreign key для `orders.driver_id`, 
`orders.operator_id` на разные таблицы, исключается ситуация при которой оператор 
назначен в заказ водителем) 
 
Сотрудники реализованы в виде read-only-model Employee, а БД view `employees`

Для того, чтобы отдавать json внешним системам создан слой ресурсов 
(`App\Http\Resources`). Такой подход позволяет убрать зависимость внутренней 
реализации модели от того, как она будет предоставлена внешним система через API. 

## Using Design Patterns

Шаблон `Criteria` используется для реализаци фильтров (`App\Filters`).
Причина выбора: Общепринятый паттерн для фильтрации чего-либо, 
повышает читабельность кода, снижает издержки на сопровождение.

Шаблон `Dependensy Injection` используется для передачи экземпляров классов в 
контроллеры. Причина выбора: Общепринятый подход и в Laravel в частности, 
и повышение читабельность кода в целом.

Шаблон `Factory` для создания объектов в тестах и для seeding database.
Причина выбора: Удобный способ создания объектов, скрывает сложные механизмы создания 
объектов за однотипным и простым интерфейсом, 
повышает читабельность кода, снижает издержки на сопровождение.

Шаблон `Middleware` используется в частности для провеки токенов для API, преимущества
в разделении кода, что позволяет сосредоточится на бизнес логике, инкапсулируя низкоуровневую 
логику логику аутентификации.


# API endpoints

Order's statuses

| Id | Status name | Order complete |  
| :---: | :--- | :---: |  
| 1 | Заказ поступил   | NO  | 
| 2 | Водитель выехал   | NO  | 
| 3 | Водитель на месте  | NO  | 
| 4 | Клиент в машине   | NO  | 
| 5 | Клиент доставлен   | NO  | 
| 6 | Заказ выполнен   | YES  | 
| 10 | Заказ не выполнен и закрыт | YES  | 

Support endpoints

| № | Name | Method | Need auth| Endpoint|  
| :---: | :--- | :--- | :---: | :--- | 
| 1 | Init app   | GET  | No | /api/v1/init |
| 2 | Get drivers | GET | No | /api/v1/drivers |
| 3 | Get employees | GET  | No | /api/v1/employees|
| 4 | Get cars   | GET  | No | /api/v1/cars |
| 5 | Create and get bearer token for Api | GET  | No | /api/v1/login  |
| 6 | Fake Api for test bearer token  | POST  | Yes | /api/v1/point-only-for-auth  |

## Drivers filters

| № | Name | Endpoint | Filter| Note | 
| :---: | :--- | :--- | :--- | :--- |
| 1 | Get drivers having orders   | /drivers  | &have[]=orders |
| 2 | Get drivers having no orders   | /drivers  |  &notHave=orders |
| 3 | Get drivers having orders with status X  | /drivers  |  &orderStatus=6 |
| 4 | Get drivers having count  of orders more than "min" and less than "max"  | /drivers  |  &haveOrdersCount={"min":1,"max":10} | You may use only min or only max format example: {"min":1} and {"max":100} |

## Drivers sorts

| № | Name | Endpoint | Filter|  
| :---: | :--- | :--- | :--- | 
| 1 | Sort drivers by count of orders | /drivers  |  &orderByOrders=ASC/DESC |

## Orders filters

| № | Name | Endpoint | Filter|  
| :---: | :--- | :--- | :--- | 
| 1 | Get orders with not status X | /orders  | &notInStatus=6 |

## Cars filters

| № | Name | Endpoint | Filter| Note|  
| :---: | :--- | :--- | :--- | :--- | 
| 1 | Get cars which have from `min` till `max` drivers in orders | /cars  | &haveDriversCount={"min":1,"max":100} | You may use only `min` or only `max` format example: `{"min":1}` and `{"max":100}` | 


## Get drivers
##### Найти всех водителей, которые не имеют ни одного заказа за всё время работы (минимум 2 в ответе)
##### GET /api/v1/drivers
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
      ...
   ],
   links:{  
        first: "http://taxi-api.local/api/v1/drivers?page=1",
        last: "http://taxi-api.local/api/v1/drivers?page=3",
        prev: null,
        next: "http://taxi-api.local/api/v1/drivers?page=2"
   },
   meta:{  
        current_page: 1,
        from: 1,
        last_page: 3,
        path: "http://taxi-api.local/api/v1/drivers",
        per_page: 5,
        to: 5,
        total: 15
   }
}
```
##### Success code is 200

## Get orders
##### GET /api/v1/orders?&dontHaveStatuses[]=6
##### Response
```json
{  
   "data":[  
      {  
         "id":6,
         "operator":"Cecile Swaniawski",
         "points":[  
            {  
               "id":13,
               "address":"982 Medhurst Manors Suite 394 Robelview, PA 54314-5251",
               "type":"pickup"
            },
            {  
               "id":14,
               "address":"80746 Merl Pike Eldoraberg, PA 03493",
               "type":"stepout"
            }
         ]
      },
      {  
         "id":9,
         "operator":"Miss Rosetta Jerde",
         "points":[  
            {  
               "id":19,
               "address":"1233 Moore Pass Mitchellburgh, IA 38561-4032",
               "type":"pickup"
            },
            {  
               "id":20,
               "address":"640 Altenwerth Ville Suite 734 Lake Luciousville, AK 69536",
               "type":"stepout"
            }
         ]
      }
      ...
   ],
   "links":{  
        "first": "http://taxi-api.local/api/v1/orders?page=1",
        "last": "http://taxi-api.local/api/v1/orders?page=20",
        "prev": null,
        "next": "http://taxi-api.local/api/v1/orders?page=2"
   },
   "meta":{  
        "current_page": 1,
        "from": 1,
        "last_page": 20,
        "path": "http://taxi-api.local/api/v1/orders",
        "per_page": 5,
        "to": 5,
        "total": 100
   }
}
```
##### Success code is 200

## Get employees
##### GET /api/v1/employees
##### Response
```json
{  
   data:[  
      {  
         id:4,
         fio:"Alexa Cormier",
         type:"operator"
      },
      {  
         id:9,
         fio:"Benedict Feeney",
         type:"driver"
      }
      ...
   ],
   links:{  
      first:"http://taxi-api.local/api/v1/employees?page=1",
      last:"http://taxi-api.local/api/v1/employees?page=6",
      prev:null,
      next:"http://taxi-api.local/api/v1/employees?page=2"
   },
   meta:{  
      current_page:1,
      from:1,
      last_page:6,
      path:"http://taxi-api.local/api/v1/employees",
      per_page:5,
      to:5,
      total:26
   }
}
```
##### Success code is 200

# How to use endpoints

## 1 Setup and authenticate

1. Run `api/v1/init` API endpoint for set up database
2. All endpoints can used without authentication, except `/api/v1/point-only-for-auth`
3. Endpoint `/api/v1/point-only-for-auth` needs adding in header `Authorization: Bearer XXX` 
4. You can get bearer token XXX in header if run POST `api/v1/login` endpoint with credentials name=`admin`,  password=`123`