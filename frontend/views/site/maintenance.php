<?php
/* @var $this yii\web\View */
$this->title = 'Maintenance';
?>
<style>
.maintenance-wrapper {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #f8f9fa;
    padding: 40px 20px;
    text-align: center;
    font-family: 'Roboto', sans-serif;
}
.maintenance-box {
    max-width: 600px;
    background: white;
    padding: 50px 30px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}
.maintenance-box h1 {
    font-size: 36px;
    color: #333;
    margin-bottom: 20px;
}
.maintenance-box p {
    font-size: 18px;
    color: #555;
    line-height: 1.6;
}
</style>

<div class="maintenance-wrapper">
    <div class="maintenance-box">
        <h1>We'll be back soon!</h1>
        <p>
            Our website is currently undergoing scheduled maintenance.<br>
            We are performing necessary updates to improve your experience.<br>
            Please check back shortly.<br><br>
            We apologize for any inconvenience and appreciate your patience.
        </p>
    </div>
</div>
