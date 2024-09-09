import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { BienvenidosPage } from './bienvenidos.page';

const routes: Routes = [
  {
    path: '',
    component: BienvenidosPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class BienvenidosPageRoutingModule {}
