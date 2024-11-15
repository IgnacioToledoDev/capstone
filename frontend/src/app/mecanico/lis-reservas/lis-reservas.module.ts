import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { LisReservasPageRoutingModule } from './lis-reservas-routing.module';

import { LisReservasPage } from './lis-reservas.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    LisReservasPageRoutingModule
  ],
  declarations: [LisReservasPage]
})
export class LisReservasPageModule {}
