import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { EscanearPatentePageRoutingModule } from './escanear-patente-routing.module';

import { EscanearPatentePage } from './escanear-patente.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    EscanearPatentePageRoutingModule
  ],
  declarations: [EscanearPatentePage]
})
export class EscanearPatentePageModule {}
