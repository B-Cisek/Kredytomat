<script setup>
import Layout from "@/Layouts/Layout.vue";
import Collapse from "@/Components/Collapse.vue";
</script>

<template>
  <Layout>
    <template v-slot:header>O Kredycie</template>
    <template v-slot:default>
      <section id="collapse">
        <Collapse class="mb-3" title="Co to jest marża?" :collapsed="false">
          <div class="p-5">
            <p>Cena po jakiej bank pożycza pieniądze. Im jest wyższa tym więcej musisz zapłacić. Nie zmienia się w
              trakcie trwania kredytu</p>
          </div>
        </Collapse>
        <Collapse class="mb-3" title="Co to jest WIBOR?" :collapsed="false">
          <div class="p-5">
            <p>Podobnie jak marża jest to cena kredytu. Jest jednak zmienna w czasie trwania pożyczki. Zmienia się co
              kilka miesięcy i może spowodować że Twoja rata wzrośnie lub zmaleje</p>
          </div>
        </Collapse>
        <Collapse class="mb-3" title="Prowizja" :collapsed="false">
          <div class="p-5">
            <p>Jednorazowa opłata jaka pobiera bank za udzielenie kredytu. Jest to koszt płatny zwykle jednorazowo na
              początku spłaty kredytu</p>
          </div>
        </Collapse>
        <Collapse class="mb-3" title="Opłaty stałe" :collapsed="false">
          <div class="p-5">
            <p>Np. ubezpieczenie nieruchomości, wynosi tyle samo przez cały czas trwania kredytu. Jego cena NIE JEST
              doliczana do wysokości raty (a to nadal miesięczny koszt)</p>
          </div>
        </Collapse>
        <Collapse class="mb-3" title="Lorem ipsum" :collapsed="false">
          <div class="p-5">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A aliquam blanditiis laborum nemo. Adipisci
              doloribus explicabo laborum mollitia neque! Consequuntur corporis dignissimos ea ex illo neque odit
              praesentium repellendus veritatis?</p>
          </div>
        </Collapse>
        <Collapse title="Lorem ipsum" :collapsed="false">
          <div class="p-5">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A aliquam blanditiis laborum nemo. Adipisci
              doloribus explicabo laborum mollitia neque! Consequuntur corporis dignissimos ea ex illo neque odit
              praesentium repellendus veritatis?</p>
          </div>
        </Collapse>
      </section>
    </template>
  </Layout>
</template>
