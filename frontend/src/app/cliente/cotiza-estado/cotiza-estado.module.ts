import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { CotizaEstadoPageRoutingModule } from './cotiza-estado-routing.module';

import { CotizaEstadoPage } from './cotiza-estado.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    CotizaEstadoPageRoutingModule
  ],
  declarations: [CotizaEstadoPage]
})
export class CotizaEstadoPageModule {}
