import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { AprobarCotizaPageRoutingModule } from './aprobar-cotiza-routing.module';

import { AprobarCotizaPage } from './aprobar-cotiza.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    AprobarCotizaPageRoutingModule
  ],
  declarations: [AprobarCotizaPage]
})
export class AprobarCotizaPageModule {}
