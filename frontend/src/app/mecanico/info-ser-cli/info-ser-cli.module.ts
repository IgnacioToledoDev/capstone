import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { InfoSerCliPageRoutingModule } from './info-ser-cli-routing.module';

import { InfoSerCliPage } from './info-ser-cli.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    InfoSerCliPageRoutingModule
  ],
  declarations: [InfoSerCliPage]
})
export class InfoSerCliPageModule {}
