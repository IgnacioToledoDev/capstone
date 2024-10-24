import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { InfoCarPageRoutingModule } from './info-car-routing.module';

import { InfoCarPage } from './info-car.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    InfoCarPageRoutingModule
  ],
  declarations: [InfoCarPage]
})
export class InfoCarPageModule {}
