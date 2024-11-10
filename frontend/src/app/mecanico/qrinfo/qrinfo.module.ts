import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { QrinfoPageRoutingModule } from './qrinfo-routing.module';

import { QrinfoPage } from './qrinfo.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    QrinfoPageRoutingModule
  ],
  declarations: [QrinfoPage]
})
export class QrinfoPageModule {}
