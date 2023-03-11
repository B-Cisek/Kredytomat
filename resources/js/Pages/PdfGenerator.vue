<script setup>
import Layout from "@/Layouts/Layout.vue";
import html2pdf from "html2pdf.js";

const exportToPDF = () => {
  html2pdf(document.getElementById('to-convert'), {
    margin: 2,
    filename: "test.pdf"
  });
};

</script>

<template>
  <Layout>
    <template v-slot:header>Generuj PDF</template>
    <template v-slot:default>
      <div id="to-convert">
        <img src="http://localhost/storage/logos/DAvqvZA9mKUhvzaSFwj3x5P4qnVwbKF3heEKL5Xn.jpg" alt="">
        <h1 class="text-red-600 font-bold text-3xl">Hello</h1>
      </div>
      <button @click="exportToPDF">Export to PDF</button>
    </template>
  </Layout>
</template>
