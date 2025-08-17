<section class="section">
  <h1 class="title">Dashboard</h1>
  <div class="columns">
    <div class="column">
      <div class="box">
        <p class="heading">Products</p>
        <p class="title"><?= e((string)$totalProducts) ?></p>
      </div>
    </div>
    <div class="column">
      <div class="box">
        <p class="heading">Low stock</p>
        <p class="title"><?= e((string)$lowStock) ?></p>
      </div>
    </div>
    <div class="column">
      <div class="box">
        <p class="heading">Today Sales</p>
        <p class="title is-inr"><?= e(format_inr($todaySalesAmount)) ?></p>
      </div>
    </div>
  </div>
</section>