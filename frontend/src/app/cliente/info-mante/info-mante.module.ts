import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { InfoMantePageRoutingModule } from './info-mante-routing.module';

import { InfoMantePage } from './info-mante.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    InfoMantePageRoutingModule
  ],
  declarations: [InfoMantePage]
})
export class InfoMantePageModule {}
