import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { ListaCotizaPageRoutingModule } from './lista-cotiza-routing.module';

import { ListaCotizaPage } from './lista-cotiza.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    ListaCotizaPageRoutingModule
  ],
  declarations: [ListaCotizaPage]
})
export class ListaCotizaPageModule {}
