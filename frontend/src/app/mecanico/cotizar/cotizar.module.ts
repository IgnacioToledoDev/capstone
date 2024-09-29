import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { CotizarPageRoutingModule } from './cotizar-routing.module';

import { CotizarPage } from './cotizar.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    CotizarPageRoutingModule
  ],
  declarations: [CotizarPage]
})
export class CotizarPageModule {}
