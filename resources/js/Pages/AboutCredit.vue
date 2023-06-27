<script setup>
import Layout from "@/Layouts/Layout.vue";
import Collapse from "@/Components/Collapse.vue";
import {Head} from "@inertiajs/inertia-vue3";
</script>

<template>
  <Head title="O kredycie"/>
  <Layout>
    <template v-slot:header>O Kredycie</template>
    <template v-slot:default>
      <section>
        <Collapse class="mb-3" title="Co to jest marża?" :collapsed="false">
          <div class="p-5 pt-0 space-y-5">
            <p>Marża w kredycie hipotecznym to dodatkowy koszt, jaki pożyczkodawca (bank lub instytucja kredytowa) narzuca na oprocentowanie kredytu. Jest to różnica między oprocentowaniem bazowym, takim jak wskaźnik referencyjny (na przykład stawka WIBOR), a rzeczywistym oprocentowaniem, które pożyczkodawca ustala dla swoich klientów.</p>
            <p>Marża jest stosowana jako metoda generowania zysku przez pożyczkodawcę i wynagrodzenia za ryzyko udzielenia kredytu. Im niższa marża, tym korzystniejsze warunki kredytu dla kredytobiorcy.</p>
            <p>Marża może być stała przez cały okres kredytowania lub mieć charakter zmiennej stopy procentowej, co oznacza, że może się zmieniać w zależności od warunków rynkowych lub umowy kredytowej. Przykładowo, jeśli pożyczkodawca ma marżę wynoszącą 2% i wskaźnik referencyjny wynosi 1%, to rzeczywiste oprocentowanie kredytu wyniesie 3%.</p>
            <p>Warto zauważyć, że marża to tylko jeden z elementów wpływających na koszt kredytu hipotecznego. Inne czynniki, takie jak opłaty, prowizje, ubezpieczenia i warunki umowy, również mają wpływ na całkowite koszty kredytu. Dlatego ważne jest, aby dokładnie przeanalizować ofertę kredytową i porównać różne marże oraz inne warunki przed podjęciem decyzji o kredycie hipotecznym.</p>
          </div>
        </Collapse>
        <Collapse class="mb-3" title="Co to jest WIBOR?" :collapsed="false">
          <div class="p-5 pt-0 space-y-5">
            <p>WIBOR (Warsaw Interbank Offered Rate) to wskaźnik referencyjny, który jest powszechnie stosowany w Polsce do określania oprocentowania kredytów hipotecznych oraz innych produktów finansowych. Jest to średnia stopa procentowa, po której banki polskie są gotowe pożyczać pieniądze od siebie nawzajem na określony okres, zwykle miesięczny.</p>
            <p>W praktyce WIBOR jest wyliczany jako średnia ważona stawek procentowych oferowanych przez wybrane banki na rynku międzybankowym. Stawki te reprezentują koszt, po jakim banki mogą pożyczać pieniądze od innych banków.</p>
            <p>WIBOR jest używany jako podstawa do obliczania oprocentowania kredytów hipotecznych. Pożyczkodawcy dodają do stawki WIBOR swoją marżę, o której wspomniałem wcześniej, aby ustalić ostateczne oprocentowanie kredytu dla klienta. Oprocentowanie kredytu hipotecznego może być ustalone na podstawie różnych okresów WIBOR, na przykład 1-miesięcznego, 3-miesięcznego, 6-miesięcznego lub rocznego.</p>
            <p>WIBOR jest regularnie publikowany i dostępny publicznie, co umożliwia kredytobiorcom śledzenie zmian i monitorowanie sytuacji na rynku finansowym. WIBOR może się zmieniać w zależności od warunków rynkowych i czynników, takich jak polityka monetarna, inflacja i popyt na kredyt.</p>
          </div>
        </Collapse>
        <Collapse class="mb-3" title="Co to jest oprocentowanie nominalne?" :collapsed="false">
          <div class="p-5 pt-0 space-y-5">
            <p>Oprocentowanie nominalne w kredycie hipotecznym to podstawowa stopa procentowa, która jest ustalana w umowie kredytowej i służy do obliczania odsetek należnych od kapitału pożyczki. Jest to procentowy koszt kredytu, który bank lub instytucja kredytowa nalicza na udzieloną kwotę kredytu.</p>
            <p>Oprocentowanie nominalne jest wyrażane w formie rocznej stopy procentowej, na przykład 4% lub 5%. Oznacza to, że bank pobiera taką część kwoty kredytu jako odsetki, co stanowi określony odsetek od salda kredytu rocznie.</p>
            <p>Warto zauważyć, że oprocentowanie nominalne nie uwzględnia innych opłat i kosztów związanych z kredytem hipotecznym, takich jak prowizje, opłaty stałe czy ubezpieczenia. Może również nie uwzględniać potencjalnych zmian stopy procentowej w trakcie okresu kredytowania.</p>
            <p>Oprocentowanie nominalne jest jednym z czynników, które wpływają na koszt kredytu hipotecznego, ale nie jest jedynym miernikiem jego atrakcyjności. Ważne jest również uwzględnienie innych czynników, takich jak marża, opłaty, warunki umowy i długość okresu kredytowania. Przed podjęciem decyzji o kredycie hipotecznym zawsze należy dokładnie przeanalizować wszystkie te czynniki i koszty, aby ocenić, która oferta jest najkorzystniejsza.</p>
          </div>
        </Collapse>
        <Collapse class="mb-3" title="Prowizja" :collapsed="false">
          <div class="p-5 pt-0 space-y-5">
            <p>Prowizja w kredycie hipotecznym to dodatkowy koszt, jaki kredytobiorca ponosi przy zawieraniu umowy kredytowej. Jest to opłata, którą bank lub instytucja kredytowa pobiera za udzielenie kredytu hipotecznego.</p>
            <p>Prowizja jest jednorazowym wydatkiem i może być pobierana na różne sposoby. Może być wyrażona jako procent wartości udzielonego kredytu lub jako stała kwota. Na ogół prowizja jest naliczana na podstawie całkowitej kwoty kredytu, chociaż niektóre instytucje mogą obliczać ją na podstawie kwoty, którą rzeczywiście wykorzystasz.</p>
            <p>Prowizja może być stosowana w celu pokrycia kosztów związanych z przetwarzaniem wniosku kredytowego, oceną ryzyka kredytowego, sporządzaniem dokumentów, działań administracyjnych i innych wydatków związanych z przyznaniem kredytu. Ponadto, prowizja jest również źródłem dochodu dla pożyczkodawcy.</p>
          </div>
        </Collapse>
        <Collapse class="mb-3" title="Opłaty stałe" :collapsed="false">
          <div class="p-5 pt-0 space-y-5">
            <p>Opłaty stałe w kredycie hipotecznym to koszty, które są pobierane jednorazowo lub okresowo przez bank lub instytucję kredytową i nie zależą od wartości kredytu. Są to opłaty administracyjne i usługowe związane z udzieleniem i obsługą kredytu hipotecznego.</p>
            <p>Oto kilka przykładów typowych opłat stałych:</p>
            <ol class="list-decimal px-5 space-y-2">
              <li>Opłata manipulacyjna: Jest to opłata pobierana za przetwarzanie i przygotowanie dokumentów związanych z kredytem hipotecznym. Może obejmować analizę wniosku, weryfikację danych, przygotowanie umowy i inne administracyjne czynności związane z kredytem.</li>
              <li>Opłata za ocenę nieruchomości: W przypadku kredytu hipotecznego bank może wymagać przeprowadzenia niezależnej oceny wartości nieruchomości, która będzie zabezpieczeniem kredytu. Ta opłata pokrywa koszty związane z zamówieniem i przeprowadzeniem takiej oceny.</li>
              <li>Opłata notarialna: Jest to opłata, którą należy uiścić notariuszowi za sporządzenie aktu notarialnego dotyczącego transakcji hipotecznej. Notariusz jest odpowiedzialny za potwierdzenie prawnego statusu transakcji oraz sporządzenie i uwierzytelnienie niezbędnych dokumentów.</li>
              <li>Opłata za wpis hipoteki: Jest to opłata, która pokrywa koszty związane z dokonaniem wpisu hipoteki w odpowiednim rejestrze, co jest niezbędne w celu zabezpieczenia interesów banku jako wierzyciela.</li>
              <li>Opłata za ubezpieczenie nieruchomości: Bank może wymagać, aby nieruchomość objęta kredytem hipotecznym była ubezpieczona od szkód i zniszczeń. Opłata za ubezpieczenie nieruchomości obejmuje koszty polisy ubezpieczeniowej.</li>
            </ol>
            <p>Warto pamiętać, że opłaty stałe mogą się różnić w zależności od banku i konkretnych warunków kredytu. Przed podjęciem decyzji o kredycie hipotecznym ważne jest zapoznanie się z pełnym harmonogramem opłat i kosztów związanych z daną ofertą kredytową.</p>
          </div>
        </Collapse>
        <Collapse class="mb-3" title="Opłaty zmienne" :collapsed="false">
          <div class="p-5 pt-0 space-y-5">
            <p>Opłaty zmienne w kredycie hipotecznym to koszty, które mogą ulegać zmianie w trakcie trwania kredytu. Są one uzależnione od różnych czynników, takich jak zmienne warunki rynkowe lub decyzje banku.</p>
            <p>Oto kilka przykładów typowych opłat zmiennych w kredycie hipotecznym:</p>
            <ol class="list-decimal px-5 space-y-2">
              <li>Roczna opłata za prowadzenie kredytu: Bank może pobierać opłatę za prowadzenie kredytu hipotecznego, która jest naliczana co roku. Ta opłata może ulegać zmianie w zależności od decyzji banku lub zmieniających się warunków umowy.</li>
              <li>Opłata za przedterminową spłatę kredytu: Bank może pobierać opłatę za przedterminową spłatę kredytu, jeśli kredytobiorca zdecyduje się spłacić całą kwotę kredytu wcześniej niż pierwotnie ustalono. Opłata ta może być uzależniona od określonego procentu salda kredytu lub innych ustalonych warunków.</li>
              <li>Opłata za zmianę warunków kredytu: Jeśli kredytobiorca chce dokonać zmiany w warunkach kredytu hipotecznego, takich jak zmiana wysokości raty, okresu kredytowania lub innych parametrów, bank może pobierać opłatę za taką zmianę.</li>
              <li>Opłata za odroczenie spłaty kapitału: W niektórych przypadkach bank może zgodzić się na odroczenie spłaty kapitału na pewien okres czasu. Jednak zazwyczaj pobierana jest opłata za takie odroczenie, która może być wyrażona jako procent od wartości odroczonego kapitału.</li>
              <li>Opłata za przedłużenie okresu kredytowania: Jeśli kredytobiorca chce przedłużyć okres kredytowania, bank może pobierać opłatę za takie przedłużenie.</li>
            </ol>
            <p>Opłaty zmienne są zależne od polityki banku i warunków umowy kredytowej. Przed podjęciem decyzji o kredycie hipotecznym warto dokładnie zapoznać się z harmonogramem opłat i warunkami związanymi z tymi kosztami.</p>
          </div>
        </Collapse>
        <Collapse class="mb-3" title="Raty równe czy malejące?" :collapsed="false">
          <div class="p-5 pt-0 space-y-5">
            <p>Raty równe charakteryzują się stałą wysokością rat przez cały okres kredytowania. Początkowo składają się one głównie z odsetek, jednak z każdą kolejną spłatą ich proporcje ulegają zmianom – zmniejsza się udział odsetek, natomiast zwiększa udział kapitału.</p>
            <p>Z kolei raty malejące zmniejszają się wraz z upływem okresu kredytowania. Ich stałą część stanowi kapitał, natomiast udział odsetek maleje wraz z każdą kolejną spłatą. Powinieneś mieć jednak świadomość, iż wysokość rat malejących na początku okresu kredytowania jest znacznie wyższa niż w przypadku spłaty w ratach równych.</p>
            <p>Powinieneś wiedzieć, iż niezależnie od rodzaju wybranej raty, jej wartość może zmieniać się w trakcie okresu kredytowania. Każdy wzrost lub spadek wartości WIBOR wpłynie na wysokość spłacanej przez Ciebie raty.</p>
            <p>Decyzja dotycząca wyboru rodzaju rat jest kwestią indywidualną, gdyż oba warianty mają swoje wady i zalety. Wybór rat malejących będzie dobrym rozwiązaniem, gdy zależy Ci na niższym całkowitym koszcie kredytu. Miej jednak na uwadze, że ich początkowa wysokość może znacznie obciążyć Twój domowy budżet. Natomiast, jeżeli liczy się dla Ciebie względnie stała i dogodna wysokość spłat, wybierz raty stałe. Pamiętaj jednak, iż ten wybór wiąże się z wyższym całkowitym kosztem zobowiązania.</p>
          </div>
        </Collapse>
        <Collapse class="mb-3" title="Oprocentowanie stałe czy zmienne?" :collapsed="false">
          <div class="p-5 pt-0 space-y-5">
            <p>Po wyborze kwoty oraz okresu kredytowania przychodzi czas na podjęcie decyzji dotyczącej rodzaju oprocentowania. W przypadku kredytu hipotecznego masz do wyboru oprocentowanie zmienne bądź oprocentowanie stałe.</p>
            <p>Wybierając oprocentowanie zmienne, musisz być przygotowany na to, że jego wysokość będzie ulegać zmianom przez cały okres kredytowania. Powinieneś wiedzieć, iż oprocentowanie jest zależne od stawki bazowej WIBOR, która zgodnie z umową będzie ulegać aktualizacji co 3 bądź co 6 miesięcy. </p>
            <p>Natomiast jeżeli zdecydujesz się na oprocentowanie stałe, możesz być pewien, że jego wysokość nie zmieni się przez okres wskazany w umowie. Po jego upływie będziesz mógł zmienić oprocentowanie na zmienne bądź przedłużyć oprocentowanie stałe na kolejny okres.</p>
          </div>
        </Collapse>
      </section>
    </template>
  </Layout>
</template>
