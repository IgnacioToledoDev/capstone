import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { ManteHistoPageRoutingModule } from './mante-histo-routing.module';

import { ManteHistoPage } from './mante-histo.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    ManteHistoPageRoutingModule
  ],
  declarations: [ManteHistoPage]
})
export class ManteHistoPageModule {}
