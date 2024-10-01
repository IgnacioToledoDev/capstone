import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { CotizarPage } from './cotizar.page';

const routes: Routes = [
  {
    path: '',
    component: CotizarPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class CotizarPageRoutingModule {}
