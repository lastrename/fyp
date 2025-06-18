<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

$this->registerCssFile('@web/css/styles.css');
$this->registerJsFile('@web/js/main.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> - Админка PlantMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<!-- Sidebar -->
<nav id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <div class="d-flex align-items-center">
            <i class="bi bi-flower1 fs-3 text-primary me-2"></i>
            <h5 class="mb-0 text-white">PlantMarket</h5>
        </div>
    </div>

    <ul class="sidebar-nav">
        <?php
        $items = [
            ['label' => 'Главная', 'icon' => 'speedometer2', 'route' => 'default/index'],
            ['label' => 'Магазины', 'icon' => 'shop', 'route' => 'shops/index'],
            ['label' => 'Категории', 'icon' => 'tags', 'route' => 'categories/index'],
            ['label' => 'Товары', 'icon' => 'box-seam', 'route' => 'products/index'],
            ['label' => 'Статьи', 'icon' => 'file-earmark-text', 'route' => 'articles/index'],
            ['label' => 'Тэги', 'icon' => 'bookmark', 'route' => 'tags/index'],
            ['label' => 'Заказы', 'icon' => 'cart', 'route' => 'orders/index'],
            ['label' => 'Отзывы', 'icon' => 'chat-dots', 'route' => 'reviews/index'],
            ['label' => 'Платежи', 'icon' => 'credit-card', 'route' => 'payments/index'],
            ['label' => 'Скидки/Акции', 'icon' => 'percent', 'route' => 'discounts/index'],
            ['label' => 'Чаты', 'icon' => 'chat-left-text', 'route' => 'chats/index'],
            ['label' => 'Пользователи', 'icon' => 'people', 'route' => 'users/index'],
            ['label' => 'Настройки', 'icon' => 'gear', 'route' => 'settings/index'],
        ];

        $currentRoute = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;

        foreach ($items as $item):
            $isActive = $currentRoute === $item['route'] ? 'active' : '';
            ?>
            <li class="nav-item">
                <?= Html::a(
                    '<i class="bi bi-' . $item['icon'] . '"></i><span>' . $item['label'] . '</span>',
                    Url::to([$item['route']]),
                    ['class' => 'nav-link ' . $isActive]
                ) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<!-- Main Content -->
<div class="main-content">
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container-fluid">
            <button class="btn btn-outline-secondary me-3" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>

            <div class="d-flex align-items-center ms-auto">
                <!-- Search -->
                <div class="me-3">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Поиск...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="dropdown me-3">
                    <button class="btn btn-outline-secondary position-relative notification-btn" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge">
                                3
                            </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end modern-notification-dropdown">
                        <div class="notification-modern-header">
                            <h6 class="notification-title">Уведомления</h6>
                            <p class="notification-subtitle">Показать все полученные сообщения</p>
                        </div>
                        <div class="notification-modern-list">
                            <div class="notification-modern-item">
                                <div class="notification-modern-icon bg-success">
                                    <i class="bi bi-cart-check"></i>
                                </div>
                                <div class="notification-modern-content">
                                    <div class="notification-modern-title">Новый заказ размещен</div>
                                    <div class="notification-modern-text">Заказ на сумму ₽2,450 от Ивана Петрова</div>
                                    <div class="notification-modern-time">2 минуты назад</div>
                                </div>
                            </div>
                            <div class="notification-modern-item">
                                <div class="notification-modern-icon bg-primary">
                                    <i class="bi bi-chat-dots"></i>
                                </div>
                                <div class="notification-modern-content">
                                    <div class="notification-modern-title">Новое сообщение получено</div>
                                    <div class="notification-modern-text">Сообщение от покупателя о доставке</div>
                                    <div class="notification-modern-time">15 минут назад</div>
                                </div>
                            </div>
                            <div class="notification-modern-item">
                                <div class="notification-modern-icon bg-warning">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </div>
                                <div class="notification-modern-content">
                                    <div class="notification-modern-title">Товар создан</div>
                                    <div class="notification-modern-text">Новый товар "Фикус Бенджамина" добавлен</div>
                                    <div class="notification-modern-time">1 час назад</div>
                                </div>
                            </div>
                            <div class="notification-modern-footer">
                                <button class="btn btn-outline-primary w-100">
                                    <i class="bi bi-arrow-right me-2"></i>
                                    Все уведомления
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary d-flex align-items-center user-menu-btn" type="button" data-bs-toggle="dropdown">
                        <div class="user-avatar-status me-2">
                            <i class="bi bi-person-circle"></i>
                            <span class="status-indicator"></span>
                        </div>
                        <span class="user-name"><?= Yii::$app->user->identity->username ?? 'Админ' ?></span>
                        <i class="bi bi-chevron-down ms-2"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= Url::to(['profile/index']) ?>"><i class="bi bi-person me-2"></i>Профиль</a></li>
                        <li><a class="dropdown-item" href="<?= Url::to(['settings/index']) ?>"><i class="bi bi-gear me-2"></i>Настройки</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= Url::to(['site/logout']) ?>" data-method="post"><i class="bi bi-box-arrow-right me-2"></i>Выйти</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="content-wrapper">
        <!-- Breadcrumb -->
        <?php if (isset($this->params['breadcrumbs'])): ?>
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><?= Html::a('Главная', Url::to(['default/index']), ['class' => 'text-decoration-none']) ?></li>
                    <?php foreach ($this->params['breadcrumbs'] as $crumb): ?>
                        <?php if (is_array($crumb)): ?>
                            <li class="breadcrumb-item"><?= Html::a($crumb['label'], $crumb['url'], ['class' => 'text-decoration-none']) ?></li>
                        <?php else: ?>
                            <li class="breadcrumb-item active" aria-current="page"><?= $crumb ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ol>
            </nav>
        <?php endif; ?>

        <!-- Flash Messages -->
        <?php foreach (Yii::$app->session->getAllFlashes() as $type => $messages): ?>
            <?php foreach ((array) $messages as $message): ?>
                <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show" role="alert">
                    <?= $message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>

        <!-- Main Content -->
        <?= $content ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
