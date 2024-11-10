import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { MantesnclienPage } from './mantesnclien.page';

const routes: Routes = [
  {
    path: '',
    component: MantesnclienPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class MantesnclienPageRoutingModule {}
