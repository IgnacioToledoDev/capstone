import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { ApproreservaPageRoutingModule } from './approreserva-routing.module';

import { ApproreservaPage } from './approreserva.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    ApproreservaPageRoutingModule
  ],
  declarations: [ApproreservaPage]
})
export class ApproreservaPageModule {}
