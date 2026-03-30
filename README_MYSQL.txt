Настройка MySQL для проекта Добрострой

1. Создайте БД и таблицы:
   mysql -u root -p < init_db.sql

2. Проверьте параметры подключения в файле db.php:
   DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS

3. Запустите проект:
   php -S localhost:8000

4. Откройте в браузере:
   http://localhost:8000/index.php

В MySQL теперь работают:
- регистрация и авторизация (таблица users)
- отзывы (таблица reviews)
- поиск товаров по первой букве (таблица products)
