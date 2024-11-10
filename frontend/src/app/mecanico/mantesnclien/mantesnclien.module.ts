import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { MantesnclienPageRoutingModule } from './mantesnclien-routing.module';

import { MantesnclienPage } from './mantesnclien.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    MantesnclienPageRoutingModule
  ],
  declarations: [MantesnclienPage]
})
export class MantesnclienPageModule {}
