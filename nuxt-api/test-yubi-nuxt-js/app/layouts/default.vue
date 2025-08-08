<template>
  <nav class="sidebar" aria-label="Primary">
    <div class="logo">
      <LayoutGrid class="icon" aria-hidden="true" />
      <span class="sr-only">Logo</span>
    </div>

    <ul class="menu" role="list">
      <li>
        <a href="#" class="border" aria-label="Tools">
          <Tool class="icon flip-x" aria-hidden="true" />
        </a>
      </li>
      <li>
        <a href="#" aria-label="Shopping Cart">
          <ShoppingCart class="icon" aria-hidden="true" />
        </a>
      </li>
      <li>
        <a href="#" aria-label="Credit Card">
          <CreditCard class="icon" aria-hidden="true" />
        </a>
      </li>
      <li>
        <a href="#" aria-label="Shopping Cart (2)">
          <ShoppingCart class="icon" aria-hidden="true" />
        </a>
      </li>
      <li>
        <a href="#" aria-label="Home">
          <Home class="icon" aria-hidden="true" />
        </a>
      </li>
      <li>
        <a href="#" aria-label="New File">
          <FilePlus class="icon" aria-hidden="true" />
        </a>
      </li>
    </ul>
  </nav>
</template>

<script setup>
import {
  LayoutGrid,
  Tool,
  ShoppingCart,
  CreditCard,
  Home,
  FilePlus,
} from 'lucide-vue-next'
</script>

<style scoped>
.sidebar {
  width: 55px;
  height: 100vh;
  position: fixed;
  inset: 0 auto 0 0; /* top:0; left:0; full height */
  background-color: #6c544d;
  color: #fff;
  padding-top: 1rem;
  display: flex;
  flex-direction: column;
  align-items: stretch;
}

.logo {
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0.75rem;
}

.menu {
  list-style: none;
  padding: 0;
  margin: 0;
}

/* anchor styles */
.menu a {
  color: #fff;
  display: block;
  padding: 10px 12.5px;
  text-decoration: none;
  border-radius: 6px; /* small rounding feels nice with hover bg */
}

.menu a:hover,
.menu a:focus-visible {
  background-color: #93746b;
}

/* optional "selected" or emphasized item */
.menu a.border {
  border: 1px solid rgba(255, 255, 255, 0.25);
}

/* icon sizing */
.icon {
  width: 24px;
  height: 24px;
}

/* your flipped-horizontal class */
.flip-x {
  transform: scaleX(-1);
}

/* a11y helper */
.sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border: 0;
}
</style>