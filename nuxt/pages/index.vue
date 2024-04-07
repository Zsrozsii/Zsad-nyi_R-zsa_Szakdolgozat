<script setup>
definePageMeta({
  middleware: [
    function (to, from) {
      const token = useCookie("apollo:web.token").value

      if (!!token) {
          return navigateTo('/dashboard')
      }
    },
  ],
});
</script>


<script>

import glass from "../assets/img/water.png"
import man from "../assets/img/man.gif"
import woman from "../assets/img/woman.gif"
import computer from "../assets/img/computer.png"

import lanygepelott00 from "../assets/img/main/lanygepelott00.png"
import kadban from "../assets/img/main/kadban.png"
import lanyi1 from "../assets/img/main/lanyi1.png"
import lanytornaa from "../assets/img/main/lanytornaa.png"
import lanyvizeel from "../assets/img/main/lanyvizeel.png"
import lanyesfiu from "../assets/img/main/lanyesfiu.png"
import csapat from "../assets/img/main/csapat.png"
import icons from "../assets/img/main/icons.png"




export default {
  name: "home",

  data() {
    return {
      glass: glass,
      manImg: man,
      womanImg: woman,
      computerImg: computer,

      lanygepelott: lanygepelott00,
      lanyesfiu: lanyesfiu,
      lanyi1: lanyi1,
      kadban: kadban,
      csapat: csapat,
      icons: icons,
    }
  }

}
</script>


<template>
<div>
  <v-sheet
      color="#141518"
      class="bg-1"
  >
    <v-container class="py-12">
      <v-row class="align-center text-center mx-auto">
        <v-col cols="12" sm="8" class="pt-8">
          <h2 class="text-h3 font-weight-black text-blue">Kövesse nyomon a napi folyadékbevitelét!</h2>
        </v-col>
        <v-col cols="12" sm="4" >
          <v-img :src="lanyi1" max-height="300" max-width="100%"/> 
        </v-col>
      </v-row>
      <v-row  class="text-left pt-4">
        <v-col class="text-right">
          <v-btn color="blue" to="/login">Go to Login </v-btn>
        </v-col>
        <v-col class="">
          <v-btn variant="outlined" color="blue" to="/register">Sign up</v-btn>
        </v-col>
      </v-row>
    </v-container>
  </v-sheet>


  <v-sheet
    class=" text-center bg-2"
    color="#141518"
  >
    <v-container class="py-16">
      <v-row>
        <v-col>
          <h3 class="text-h4 text-blue">Miért fontos a hidratáció?</h3>
        </v-col>
      </v-row>
      <v-row justify="center" class="justify-center">
        <v-col cols="12" sm="4" class="align-self-center">
          <v-img :src="lanyesfiu" max-height="250" max-width="100%"/>
        </v-col>
        <v-col cols="12" sm="8" class="pa-8 text-body-1 align-self-center" >
          <p>
            Gondoljunk arra, hogy mire is van szüksége az embernek ahhoz, hogy túlélje. Ténylegesen csakis arra, hogy túlélje a testünk az életet. Levegőre? Vízre? Élelmiszerre? Facebookra?
            Ezek közül vajon minden fontos? Mi most csak a vízre fogunk kitérni, ez az egyik fontos hozzávaló az élethez, nem csak az emberek számára elsődleges dolog a víz, hanem minden élőlény számára. Bizonyos szervezetekben a víz akár a testtömegük 90%-ból származik. A felnőtt emberi testnek a víz százalék tartalma elérheti akár a 60%-t.
          </p>
        </v-col>
      </v-row>
    </v-container>
  </v-sheet>


  <v-sheet
        color="#141518"
        class=" text-center bg-3"
  >
    <v-container class="py-16">
      <v-row>
        <v-col>
          <h3 class="text-h4 text-white">A víz és az emberi test</h3>
        </v-col>
      </v-row>
      <v-row class="justify-center">
        <v-col cols="12" sm="8" class="pa-8 text-body-1 align-self-center">
          <p>
            1945-s kutatásban leírták, hogy bizonyos emberi szervek összetételében hány százalék tartalmaz vizet. Ilyen szerv a szív és az agy, ezekben a víz százaláka meghaladja a 73%-t is. A tüdőben körülbelül 83 százalék víz található. A bőrben a víz 64 százalékben található meg, míg az izmokban és a vesékben 79 szazalák van. Képzeljék el, hogy a csontok 31 százalákban tartalmaznak vizet. 
          </p>
        </v-col>
        <v-col cols="12" sm="4" class="align-self-center">
          <v-img :src="kadban" max-height="200" max-width="100%"/> 
        </v-col>
      </v-row>
    </v-container>
  </v-sheet>


  <v-sheet
  class=" text-center bg-4"
  color="#141518"
  >
    <v-container class="py-16">
      <v-row>
        <v-col>
          <h3 class="text-h4 text-blue">Mennyi vizet kell fogyasztani?</h3>
        </v-col>
      </v-row>
      <v-row class="justify-center">
        <v-col class="pa-8 text-body-1" >
          <p>
            Minden élőlénynek minden nap bizonyos mennyiségő folyadékra van szüksége arra, hogy tovább tudjon élni. Természetesen ez fajtól, kortól, nemtől függően változik. Itt természetesen az ember napi folyadékbevitelét foglyuk nyomon követni.
Egy átlagos felnőtt férfinak körülbelül 3 liter folyadékra van szükség naponta, ezt a folyadákot sokféleképpen be lehet vinni, de nem minden ad kellő hidratáltságot az embernek. Egy átlagos felnőtt nőnek, a férfiakhoz képest kevesebb folyadékot kell bevinniük, körölbelül 2,2 literre van szökség a szervezetüknek. Természetesen ezek az adatok nem pontosak, meg nem mindneki végzi ugyan azt a munkát, sport, nem élnek ugyanazon az égtályan. Sok külső tényező is hozzátesz ahhoz, hogy mennyi egy ember napi ideállis folyadékbevitele. 
          </p>
        </v-col>
        
      </v-row>
      <v-row>
        <v-col>
          <v-img :src="csapat" max-height="200" max-width="100%"/> 
        </v-col>
      </v-row>
    </v-container>
  </v-sheet>

  
  <v-sheet
      color="#141518"
      class="bg-5"
  >
    <v-container class="py-16">
      <v-row>
        <v-col class="text-center">
          <h3 class="text-h4">Miért fontos hogy megfelelő mennyiséget vigyünk be a szervezetünkbe?</h3>
          <h5 class="text-h6 font-weight-light mt-3">A víz számos alapvető funkciókkal lát el bennünket a mindennapi élethez, ilyen például:</h5>
        </v-col>
      </v-row>
      <v-row class="mt-6">
        
        <v-col cols="12" sm="6" class="pa-8 text-body-1" >
          <ul>
            <li>
              Izzadás: azért fontos számunkra az izzadás mert ezzel és a lélegzéssel tudjuk szabályozni a belső testhőmérsékletünket, és még méregtelenít is.
            </li>
            <li>Véráramban: étkezéskor bevitt szénhidrátokat és fehérjéket, amire a testünkben fontos szerepeket szolgálnak, ezeknek a megfelelő helyre való jutásához a víz felelős, hiszen a vérármban a víznak a segítségével szállítódik.</li>
            <li>Öblítés: Segít kijuttatni a fel nem használt anyagokat, hulladékokat túlnyomó részt vizelet segíségével.</li>
          </ul>
        </v-col>
        <v-col cols="12" sm="6" class="pa-8 text-body-1" >
          <li>
            Lengéscsillapító: Lengéscsillapítóként funkcionál az agynál, a gerincvelőnél, és még a magzatnál is.
          </li>
          <li>
            Nyál: fontos szerepe van a nyál termelődésében
          </li>
          <li>Izületek: az izületek felépítésében fontos szerepe van, bizonyos kenőanyag szerepet játszik</li>
          
        </v-col>
      </v-row>
      <v-row>
          <v-col>
            <v-img :src="icons" max-height="200" max-width="100%"/> 
          </v-col>
        </v-row>
    </v-container>
  </v-sheet>

  <v-sheet
  color="#141518"
  class=" text-center bg-6"
  >
    <v-container class="py-16">
      <v-row>
        <v-col>
          <h3 class="text-h4 text-blue">Miért hasznos a folyadékbevitel-nyomonkövető alkalmazás?</h3>
        </v-col>
      </v-row>
      <v-row class="justify-center">
        <v-col cols="12" sm="8" class="pa-8 text-body-1 align-self-center">
          <p>
            Sokak számára a folyadékbevitel nem épül be a napi tevékenységekbe. Sokszor elfelejtkezünk róla, és csak gyors valami ételt viszünk be a szervezetünkbe. Pedig sokszor a szervezet csak folyadékra vágyik és azért érezzük a éhség érzetét. Mikor ez már renszeressé válik el kezd fájni a fejünk, fáradékonyabbak válunk. 
            Ezért is fontos, hogy erről a fontos tevékenységről ne felejtkezzünk el.
          </p>
        </v-col>
        <v-col  cols="12" sm="4" class="align-self-center">
          <v-img :src="computerImg" max-height="250" max-width="100%" /> 
        </v-col>
      </v-row>
    </v-container>
  </v-sheet>
  
</div>

</template>


<style scoped>

.bg-1 {
  background: url('../assets/img/main/1.jpg');
  background-size: cover;
}

.bg-2 {

}

.bg-3 {
  background: url('../assets/img/main/2.jpg');
  background-size: cover;
}

.bg-4 {

}

.bg-5 {
  background: url('../assets/img/main/3.jpg');
  background-size: cover;
}

.bg-6 {
  
}

.text-body-1 {

}

</style>



