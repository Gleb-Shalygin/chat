#Чат на WebSockets
___
##Описание

Чат работает на WebSocket с использованием библиотеки Ratchet. 
Имеется возможность создавать свою комнату и поставить на 
неё пароль или же просто оставить открытой. Когда пользователь 
пытается зайти в комнату, которая находится под паролем, то ему выскакивает ошибка. 
Основные библиотеки: для подключения MySql библиотека Krugozor; 
для роутинга библиотека Slim; в качестве шаблонизатора библиотека Twig;


##Маршруты и обзор
+ https://chat/
  ![img.png](assets/readme/img/main.png)
+ https://chat/store
  ![img_3.png](assets/readme/img/store.png)
+ https://chat/main/chat
  ![img_1.png](assets/readme/img/chat.png)