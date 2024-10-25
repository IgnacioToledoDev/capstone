import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { SeguimientoCliPageRoutingModule } from './seguimiento-cli-routing.module';

import { SeguimientoCliPage } from './seguimiento-cli.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    SeguimientoCliPageRoutingModule
  ],
  declarations: [SeguimientoCliPage]
})
export class SeguimientoCliPageModule {}
