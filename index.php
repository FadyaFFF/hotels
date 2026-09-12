<?php
// Подключение к БД
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'hotels_db';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Выбираем все отели
$sql = "SELECT * FROM hotels ORDER BY id";
$result = $conn->query($sql);
$hotels = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $hotels[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отели — как на Avito</title>
    <!-- Подключаем шрифты и иконки (Font Awesome) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Глобальные стили */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background: #f0f2f5;
            padding: 30px 20px;
        }
        .container {
            max-width: 1300px;
            margin: 0 auto;
        }
        h1 {
            font-size: 28px;
            font-weight: 500;
            margin-bottom: 25px;
            color: #222;
        }
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 25px;
        }
        /* Карточка как на Avito */
        .card {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.15s, box-shadow 0.15s;
            cursor: default;
            display: flex;
            flex-direction: column;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        .card-image {
            width: 100%;
            height: 180px;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .card-body {
            padding: 16px 18px 18px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .card-title {
            font-size: 17px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 6px;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 44px;
        }
        .card-price {
            font-size: 22px;
            font-weight: 700;
            color: #e53e3e;
            margin: 8px 0 6px;
        }
        .card-price small {
            font-size: 14px;
            font-weight: 400;
            color: #6c757d;
            margin-left: 4px;
        }
        .card-description {
            font-size: 14px;
            color: #4a4a4a;
            line-height: 1.5;
            margin: 6px 0 12px;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 63px;
        }
        .card-footer {
            border-top: 1px solid #f0f0f0;
            padding-top: 12px;
            margin-top: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: #6c757d;
        }
        .card-footer i {
            margin-right: 4px;
        }
        .card-footer .location {
            display: flex;
            align-items: center;
        }
        .no-data {
            text-align: center;
            font-size: 18px;
            color: #888;
            padding: 60px 0;
        }
        /* Адаптив */
        @media (max-width: 600px) {
            .card-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
        @media (max-width: 400px) {
            .card-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1><i class="fas fa-hotel" style="color: #2b6c94;"></i> Найдено отелей: <?= count($hotels) ?></h1>
    <div class="card-grid">
        <?php if (count($hotels) > 0): ?>
            <?php foreach ($hotels as $hotel): ?>
                <div class="card">
                    <div class="card-image">
                        <!-- Если в БД есть своя картинка, используем её, иначе заглушка -->
                        <img src="<?= htmlspecialchars($hotel['image_url'] ?? 'https://via.placeholder.com/300x200/cccccc/666?text=Hotel') ?>" alt="<?= htmlspecialchars($hotel['name']) ?>">
                    </div>
                    <div class="card-body">
                        <div class="card-title"><?= htmlspecialchars($hotel['name']) ?></div>
                        <div class="card-price"><?= number_format($hotel['price'], 0, '.', ' ') ?> ₽ <small>за ночь</small></div>
                        <div class="card-description"><?= htmlspecialchars($hotel['description']) ?></div>
                        <div class="card-footer">
                            <span><i class="fas fa-bed"></i> 2–4 места</span>
                            <span class="location"><i class="fas fa-map-marker-alt"></i> Россия</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-data">Нет отелей. Запустите <strong>create_data.php</strong> для наполнения БД.</div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>