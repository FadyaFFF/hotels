<?php
// Параметры подключения к MySQL (стандартные для XAMPP)
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'hotels_db';

// Подключаемся без выбора БД
$conn = new mysqli($host, $user, $password);
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Создаём БД, если её нет
$sqlCreateDb = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sqlCreateDb) === TRUE) {
    echo "База данных '$dbname' создана или уже существует.<br>";
} else {
    die("Ошибка создания БД: " . $conn->error);
}

// Выбираем созданную БД
$conn->select_db($dbname);

// Создаём таблицу hotels (с полем image_url)
$tableSql = "CREATE TABLE IF NOT EXISTS hotels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    image_url VARCHAR(255) DEFAULT 'https://via.placeholder.com/300x200?text=Hotel'
)";
if ($conn->query($tableSql) === TRUE) {
    echo "Таблица 'hotels' создана или уже существует.<br>";
} else {
    die("Ошибка создания таблицы: " . $conn->error);
}

// Проверяем, есть ли уже записи
$checkSql = "SELECT COUNT(*) as cnt FROM hotels";
$result = $conn->query($checkSql);
$row = $result->fetch_assoc();

// Ваш массив с новыми ссылками
$hotels = [
    ['Grand Royal', 12500, 'Роскошный отель в центре города с видом на море. Бассейн, спа, ресторан.', 'https://avatars.mds.yandex.net/i?id=4b6fd24fb968e1af35eaa6aead55528a_l-5342217-images-thumbs&n=13'],
    ['Sunny Beach', 8900, 'Уютный отель на первой линии пляжа. Идеально для семейного отдыха.', 'https://avatars.mds.yandex.net/i?id=b2f3e7dbc0e1dc0c25343db68405c145_l-9858735-images-thumbs&n=13'],
    ['Mountain View', 10200, 'Отель в горах с панорамным видом. Термальные источники рядом.', 'https://img.freepik.com/free-photo/modern-luxurious-bedroom-elegant-comfortable-design-generated-by-ai_188544-29281.jpg?semt=ais_hybrid&w=740&q=80'],
    ['City Center Inn', 6700, 'Бюджетный вариант в самом сердце мегаполиса. Рядом метро и парки.', 'https://i.pinimg.com/originals/de/fd/c9/defdc9bb2fa55c87a991aee661a8a60c.jpg?nii=t'],
    ['Luxury Palace', 21500, 'Элитный отель с индивидуальным обслуживанием. Мишленовский ресторан.', 'https://i.pinimg.com/736x/21/64/6e/21646ee1a8da55ad5feee59475728f5a.jpg'],
    ['Eco Village', 7800, 'Эко-отель в лесу. Свежий воздух, органическая кухня, йога.', 'https://i.pinimg.com/originals/c4/e0/9d/c4e09d931f949654c3135c084cfab652.png'],
    ['Sea Breeze', 9500, 'Современный отель с выходом к частному пляжу. Аквапарк.', 'https://i.pinimg.com/474x/2f/90/2d/2f902d2de30caed3e5dc204641635041.jpg?nii=t'],
    ['Old Town Hostel', 3500, 'Колоритный хостел в историческом районе. Отличная атмосфера.', 'https://i.pinimg.com/474x/69/84/3f/69843fbe693597f79bc3a63a31f66d3a.jpg?nii=t']
];

if ($row['cnt'] == 0) {
    // Первичное добавление записей
    $stmt = $conn->prepare("INSERT INTO hotels (name, price, description, image_url) VALUES (?, ?, ?, ?)");
    foreach ($hotels as $h) {
        $stmt->bind_param("sdss", $h[0], $h[1], $h[2], $h[3]);
        $stmt->execute();
    }
    $stmt->close();
    echo "8 отелей с вашими картинками успешно добавлены.<br>";
} else {
    // Если записи уже есть – обновляем только картинки (по названию отеля)
    echo "В таблице уже есть записи. Обновляем картинки...<br>";
    $stmt = $conn->prepare("UPDATE hotels SET image_url = ? WHERE name = ?");
    foreach ($hotels as $h) {
        $stmt->bind_param("ss", $h[3], $h[0]); // сначала URL, потом название
        $stmt->execute();
    }
    $stmt->close();
    echo "Картинки обновлены на ваши!<br>";
}

$conn->close();
echo "Готово! Перейдите на <a href='index.php'>index.php</a> для просмотра карточек с новыми фото.";
?>